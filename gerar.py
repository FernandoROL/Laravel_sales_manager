from pathlib import Path
import csv
import hashlib
import io
import json
import os
import shutil
import subprocess
import zipfile

from PIL import Image
from pypdf import PdfReader
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.pdfgen.canvas import Canvas
from reportlab.platypus import Image as Picture, Paragraph, Table, TableStyle

ROOT = Path.cwd()
DATA = json.loads((ROOT / "editorial.json").read_text())
OUT = ROOT / "arquivos"
OUT.mkdir(exist_ok=True)
WORK = Path(os.environ["RUNNER_TEMP"]) / "pacote-vendas"
EVID = WORK / "evidencias"
EVID.mkdir(parents=True, exist_ok=True)
REPO = os.environ["GITHUB_REPOSITORY"]
PROJECT = Path(os.environ["PROJECT_DIR"])
def command(*args):
    return subprocess.check_output(args)
def git(*args):
    return command("git", *args)
def api(path):
    return command("gh", "api", "--allow-escape-sequences", "repos/" + REPO + "/" + path)
def json_api(path):
    return json.loads(api(path))

base, before, after = DATA["commits"]
original = git("show", base + ":.github/workflows/scans.yml")
assert hashlib.sha256(original).hexdigest() == DATA["original_sha256"], "O anexo original divergiu"
(EVID / "scans-original-do-professor.yml").write_bytes(original)
(EVID / "correcoes.diff").write_bytes(git("diff", before, after))
(EVID / "teste-http.txt").write_text(DATA["demo"] + "\n")
(EVID / "sha256-anexo.txt").write_text(DATA["original_sha256"] + "  scans-original-do-professor.yml\n")

def read_source(stage, run_id, sha):
    folder = EVID / stage
    folder.mkdir(exist_ok=True)
    run = json_api(f"actions/runs/{run_id}")
    assert run["head_sha"] == sha and run["conclusion"] == "success"
    meta = {k:run[k] for k in ("id","head_sha","html_url","name","run_attempt","conclusion","created_at")}
    jobs = json_api(f"actions/runs/{run_id}/jobs")["jobs"]
    meta["jobs"] = [{"id":j["id"], "name":j["name"], "conclusion":j["conclusion"]} for j in jobs]
    assert all(j["conclusion"] == "success" for j in jobs)
    meta["artefatos"] = []
    for j in jobs:
        if j["name"] in ("SAST - Semgrep","SCA - OSV-Scanner"):
            log = api(f"actions/jobs/{j['id']}/logs").decode("utf-8-sig",errors="replace")
            lines = [line for line in log.splitlines() if ("Ran " in line and "rules on " in line) or ("composer.lock file and found" in line)]
            (folder / ("cobertura-" + j["name"].split(" ")[0].lower() + ".txt")).write_text("\n".join(lines)+"\n")
    raw = {}
    if stage != "original":
        artifacts = json_api(f"actions/runs/{run_id}/artifacts")["artifacts"]
        for kind in ("semgrep", "osv"):
            a = next(x for x in artifacts if x["name"] == kind + "-" + sha)
            payload = api(f"actions/artifacts/{a['id']}/zip")
            assert "sha256:"+hashlib.sha256(payload).hexdigest() == a["digest"]
            meta["artefatos"].append({k:a[k] for k in ("id","name","digest")})
            with zipfile.ZipFile(io.BytesIO(payload)) as z:
                content = z.read(kind+".json")
            (folder/(kind+".json")).write_bytes(content)
            raw[kind] = json.loads(content)
    (folder/"execucao.json").write_text(json.dumps(meta,indent=2,ensure_ascii=False))
    (folder/"overview.md").write_text(DATA["summaries"][stage])
    return raw

raw = {stage:read_source(stage,run,sha) for stage,run,sha in zip(("original","antes","depois"),DATA["scan_runs"],DATA["commits"])}
def advisories(stage):
    found = {}
    for r in raw[stage]["osv"]["results"]:
        for item in r["packages"]:
            pkg = item["package"]
            groups = item.get("groups") or [{"ids":[v["id"]]} for v in item["vulnerabilities"]]
            for group in groups:
                ref = group["ids"][0]
                found[pkg["name"],ref] = pkg["version"]
    return found
old,new = advisories("antes"),advisories("depois")
assert len(old)==49 and len(new)==44 and set(new) <= set(old)
with (EVID/"inventario-sca.csv").open("w",newline="",encoding="utf-8-sig") as f:
    w=csv.writer(f);w.writerow(["Dependência","Aviso OSV","Versão antes","Versão no lockfile corrigido","Resultado"])
    lock=json.loads((PROJECT/"composer.lock").read_text())
    installed={p["name"]:p["version"] for p in lock["packages"]+lock["packages-dev"]}
    for (pkg,id_),ver in sorted(old.items()):
        w.writerow([pkg,id_,ver,installed[pkg],"permanece" if (pkg,id_) in new else "ausente"])
before_sast,after_sast = (raw[k]["semgrep"]["results"] for k in ("antes","depois"))
assert len(before_sast)==5 and len(after_sast)==1 and "detected-bcrypt-hash" in after_sast[0]["check_id"]
with (EVID/"inventario-sast.csv").open("w",newline="",encoding="utf-8-sig") as f:
    w=csv.writer(f);w.writerow(["Regra","Arquivo","Linha no antes","Classificação","Estado final"])
    for v in before_sast:
        existing=any((a["path"],a["check_id"])==(v["path"],v["check_id"]) for a in after_sast)
        w.writerow([v["check_id"],v["path"],v["start"]["line"],"FP contextual" if existing else "VP","permanece" if existing else "ausente"])
shutil.copy(ROOT/"dashboard.png",EVID/"dashboard.png")
img=Image.open(EVID/"dashboard.png")
assert img.width>=1100 and len(img.convert("RGB").resize((120,70)).getcolors(10000))>40
fontdir="/usr/share/fonts/truetype/dejavu/"
for alias,name in [("Text","DejaVuSans"),("Bold","DejaVuSans-Bold"),("Title","DejaVuSerif"),("Mono","DejaVuSansMono")]:
    pdfmetrics.registerFont(TTFont(alias,fontdir+name+".ttf"))
pdfmetrics.registerFontFamily("Text",normal="Text",bold="Bold")
ink,teal,gray="#15323C","#08756B","#455D64"
def style(size,face="Text",colour=ink):
    return ParagraphStyle("body",fontName=face,fontSize=size,leading=size*1.45,textColor=colors.HexColor(colour),splitLongWords=1)
def render(filename,pages,slides):
    W,H=(960,540) if slides else A4
    margin=43
    width=W-2*margin
    c=Canvas(str(OUT/filename),pagesize=(W,H))
    c.setTitle(filename.replace("-"," ").removesuffix(".pdf"))
    for n,page in enumerate(pages,1):
        c.setFillColor(colors.HexColor("#F8FAF8"));c.rect(0,0,W,H,stroke=0,fill=1)
        c.setFillColor(colors.HexColor(teal));c.rect(margin,H-35,36,3,stroke=0,fill=1)
        c.setFont("Bold",10);c.drawString(margin,H-54,page["tag"].upper())
        def build(scale):
            parts=[]
            def add(ob,gap):
                _,ht=ob.wrap(width,2000)
                parts.append((ob,ht,gap*scale))
            add(Paragraph(page["title"],style((32 if slides else 23)*scale,"Title")),13)
            for item in page["body"]:
                typ,*a=item
                if typ in ("p","note","h"):
                    size={"p":17 if slides else 10.8,"h":20 if slides else 13.5,"note":14 if slides else 10}[typ]
                    st=style(size*scale,"Bold" if typ=="h" else "Text",teal if typ in ("h","note") else ink)
                    add(Paragraph(a[0],st),10 if slides else 7)
                elif typ=="code":
                    pp=[(Paragraph(t,style((15 if slides else 10)*scale,"Mono")),) for t in a[0]]
                    t=Table(pp,colWidths=[width]);t.setStyle(TableStyle([("BACKGROUND",(0,0),(-1,-1),colors.HexColor("#E1F0EA")),("LEFTPADDING",(0,0),(-1,-1),12),("TOPPADDING",(0,0),(-1,-1),6),("BOTTOMPADDING",(0,0),(-1,-1),6)]));add(t,12)
                elif typ=="image":
                    ob=Picture(str(EVID/"dashboard.png"))
                    maxh=(270 if slides else 170)*scale
                    k=min(width/ob.imageWidth,maxh/ob.imageHeight);ob.drawWidth=ob.imageWidth*k;ob.drawHeight=ob.imageHeight*k
                    add(ob,7)
                elif typ=="table":
                    headers,rows,ratios=a;size=(14.3 if slides else 9.3)*scale
                    vals=[[Paragraph(str(t),style(size,"Text" if i else "Bold",ink if i else "#FFFFFF")) for t in row] for i,row in enumerate([headers]+rows)]
                    t=Table(vals,colWidths=[x*width for x in ratios]);pad=(9 if slides else 6)*scale
                    t.setStyle(TableStyle([("BACKGROUND",(0,0),(-1,0),colors.HexColor("#173E45")),("ROWBACKGROUNDS",(0,1),(-1,-1),[colors.HexColor("#E8F1ED"),colors.HexColor("#F1F5F2")]),("VALIGN",(0,0),(-1,-1),"TOP"),("TOPPADDING",(0,0),(-1,-1),pad),("BOTTOMPADDING",(0,0),(-1,-1),pad),("LEFTPADDING",(0,0),(-1,-1),pad),("RIGHTPADDING",(0,0),(-1,-1),pad)]));add(t,11)
            return parts
        top=H-71
        for pct in range(100,74,-1):
            parts=build(pct/100)
            if sum(ht+gap for _,ht,gap in parts) < top-44: break
        else: raise ValueError("Texto não coube na página "+page["title"])
        y=top
        for obj,ht,gap in parts:
            y-=ht;obj.drawOn(c,margin,y);y-=gap
        c.setStrokeColor(colors.HexColor("#BFD4CD"));c.line(margin,35,W-margin,35)
        c.setFont("Text",8.5);c.setFillColor(colors.HexColor(gray));c.drawString(margin,21,"Laravel Sales Manager   /   Segurança de aplicações em Docker")
        c.drawRightString(W-margin,21,f"{n} / {len(pages)}");c.showPage()
    c.save()
    read=PdfReader(str(OUT/filename))
    assert len(read.pages)==len(pages)
    for page,expected in zip(read.pages,pages):
        assert expected["title"].split()[0] in page.extract_text()
    assert sum(len(p.get("/Annots",[])) for p in read.pages) >= 4

render("Apresentacao-Seguranca-Docker.pdf",DATA["slides"],True)
render("Reportagem-Seguranca-Docker.pdf",DATA["report"],False)
readme="""# Entrega acadêmica · Laravel Sales Manager em Docker

Comece por Reportagem-Seguranca-Docker.pdf para acompanhar as decisões. Use Apresentacao-Seguranca-Docker.pdf na exposição oral.

A pasta projeto/ reproduz o commit corrigido. Com Docker Compose v2.24 ou posterior, entre nela e execute:

    docker compose up --build -d
    docker compose exec sistema_gestao php artisan db:seed

Abra http://localhost:8989/dashboard e depois http://localhost:8989/salles. Clique em Email Receipt e confira o assunto Email Sale Recipt com:

    docker compose exec sistema_gestao tail -n 60 storage/logs/laravel.log

Em evidencias/, comece pelos três overview.md. A pasta original/ tem o resumo do YAML exato do professor. As pastas antes/ e depois/ preservam os arquivos brutos semgrep.json e osv.json. Consulte inventario-sca.csv para todos os avisos, incluindo as pendências, e correcoes.diff para a comparação de código. Os IDs e hashes de cada artefato constam nos arquivos execucao.json.

O resumo de SCA do professor mostra os primeiros 30 avisos. O CSV e os JSONs da comparação são completos. Os comandos de demonstração criam dados fictícios e o transporte local grava o recibo no log.
"""
(WORK/"LEIA-ME.md").write_text(readme)
with zipfile.ZipFile(OUT/"Codigo-e-Evidencias-Docker.zip","w",compression=zipfile.ZIP_DEFLATED,compresslevel=9) as z:
    with zipfile.ZipFile(io.BytesIO(git("archive","--format=zip",after))) as src:
        for entry in src.infolist():
            if not entry.is_dir():
                data=src.read(entry)
                entry.filename="projeto/"+entry.filename
                z.writestr(entry,data)
    for file in sorted(WORK.rglob("*")):
        if file.is_file():z.write(file,file.relative_to(WORK))
    for pdf in sorted(OUT.glob("*.pdf")):z.write(pdf,pdf.name)
with zipfile.ZipFile(OUT/"Codigo-e-Evidencias-Docker.zip") as z:
    assert z.testzip() is None
    assert "projeto/artisan" in z.namelist()
    assert z.read("projeto/composer.lock")==git("show",after+":composer.lock")
print("PDFs, referências, escopo, dados de origem e integridade do ZIP verificados.")

if shutil.which("pdftoppm"):
    from base64 import b64encode
    thumbs = []
    for name,n in [("Apresentacao-Seguranca-Docker.pdf",2),("Apresentacao-Seguranca-Docker.pdf",5),("Reportagem-Seguranca-Docker.pdf",2),("Reportagem-Seguranca-Docker.pdf",3)]:
        target=Path(os.environ["RUNNER_TEMP"])/("pagina-"+str(len(thumbs)))
        command("pdftoppm","-f",str(n),"-l",str(n),"-singlefile","-scale-to","400","-png",str(OUT/name),str(target))
        thumbs.append(Image.open(str(target)+".png").convert("RGB"))
    sheet = Image.new("RGB",(810,644),"#dadee0")
    for n,im in enumerate(thumbs):
        sheet.paste(im,((n%2)*405,(n//2)*238))
    buffer=io.BytesIO();sheet.save(buffer,format="JPEG",quality=72)
    print("PREVIA_PDF="+b64encode(buffer.getvalue()).decode())
