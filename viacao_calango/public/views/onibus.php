<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">

<style>
body{margin:0;background:#000;color:#fff;font-family:Arial}
.container{padding:20px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:25px}
.card{
    background:#3a3a3a;
    padding:20px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.name{font-size:18px;font-weight:bold}
.label{font-size:14px;opacity:.8}

.btn-problem{
    width:28px;height:28px;border-radius:50%;
    border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    font-weight:bold;font-size:15px;color:#000;
}

.btn-edit{
    padding:7px 12px;border:none;border-radius:8px;
    background:#1e90ff;color:#fff;
    cursor:pointer;font-weight:bold;
}

.btn-del{
    padding:7px 12px;
    border:none;border-radius:8px;
    background:#ff3b3b;color:#000;
    cursor:pointer;font-weight:bold;
}

.addBtn{
    background:#ffd600;color:#000;
    padding:10px 15px;
    border-radius:10px;
    border:none;
    cursor:pointer;
    font-weight:bold;
}

.modal-back{
    position:fixed;inset:0;background:rgba(0,0,0,.75);
    display:none;justify-content:center;align-items:center;
}

.modal{
    width:360px;background:#1f1f1f;border-radius:12px;padding:20px;
}

.input{
    width:100%;padding:10px;margin-bottom:12px;
    border:none;border-radius:8px;
}

.btn-save{
    background:#ffd600;color:#000;
    padding:10px;border:none;border-radius:8px;
    cursor:pointer;font-weight:bold;
}

.btn-cancel{
    background:#333;color:#fff;
    padding:10px;border:none;border-radius:8px;
    cursor:pointer;
}

.msg{margin-top:10px;color:#ffd600;display:none}
</style>

</head>
<body>

<div class="container">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h2>Ônibus</h2>
        <button class="addBtn" id="openAdd">Adicionar</button>
    </div>

    <div class="grid" id="busGrid">
        <?php foreach ($buses as $b): ?>
            <div class="card" data-id="<?= $b['id'] ?>">
                <div>
                    <div class="name"><?= htmlspecialchars($b['name']) ?></div>
                    <div class="label"><?= (int)$b['seats'] ?> assentos</div>
                </div>

                <div style="display:flex;gap:10px;align-items:center;">
                    <button class="btn-problem"
                        style="background:<?= $b['problem'] ? '#ffd600' : '#666' ?>">
                        !
                    </button>

                    <button class="btn-edit editBus"
                        data-id="<?= $b['id'] ?>"
                        data-name="<?= htmlspecialchars($b['name']) ?>"
                        data-seats="<?= (int)$b['seats'] ?>">
                        Editar
                    </button>

                    <button class="btn-del">Excluir</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<div class="modal-back" id="modalBack">
    <div class="modal">
        <h3>Adicionar ônibus</h3>

        <input id="mName" class="input" placeholder="Nome">
        <input id="mSeats" type="number" class="input" placeholder="Assentos">

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
            <button class="btn-cancel" id="cancel">Cancelar</button>
            <button class="btn-save" id="save">Salvar</button>
        </div>

        <div class="msg" id="msg"></div>
    </div>
</div>

<div class="modal-back" id="editModalBack">
    <div class="modal">
        <h3>Editar ônibus</h3>

        <input type="hidden" id="edit_id">

        <input id="edit_name" class="input" placeholder="Nome">
        <input id="edit_seats" type="number" class="input" placeholder="Assentos">

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
            <button class="btn-cancel" id="edit_cancel">Cancelar</button>
            <button class="btn-save" id="edit_save">Salvar</button>
        </div>

        <div class="msg" id="edit_msg"></div>
    </div>
</div>

<script>
function post(data){
    return fetch(location.href,{method:"POST",body:data}).then(r=>r.json());
}

let modal = document.getElementById("modalBack");
let msg = document.getElementById("msg");

document.getElementById("openAdd").onclick = () => {
    mName.value = "";
    mSeats.value = "";
    msg.style.display = "none";
    modal.style.display = "flex";
};

document.getElementById("cancel").onclick = () => modal.style.display="none";
modal.onclick = e => { if(e.target === modal) modal.style.display="none"; };

document.getElementById("save").onclick = async () => {
    let name = mName.value.trim();
    let seats = mSeats.value;

    if(!name || seats <= 0){
        msg.textContent = "Preencha nome e assentos.";
        msg.style.display = "block";
        return;
    }

    let fd = new FormData();
    fd.append("action","add");
    fd.append("name",name);
    fd.append("seats",seats);

    let r = await post(fd);
    if(!r.ok){
        msg.textContent = r.msg;
        msg.style.display = "block";
        return;
    }

    let grid = document.getElementById("busGrid");
    let div = document.createElement("div");
    div.className = "card";
    div.dataset.id = r.id;
    div.innerHTML = `
        <div>
            <div class="name">${name}</div>
            <div class="label">${seats} assentos</div>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <button class="btn-problem" style="background:#666">!</button>
            <button class="btn-edit editBus"
                data-id="${r.id}"
                data-name="${name}"
                data-seats="${seats}">Editar</button>
            <button class="btn-del">Excluir</button>
        </div>
    `;
    grid.insertBefore(div, grid.firstChild);
    modal.style.display="none";
};

document.addEventListener("click", async e=>{
    if(e.target.classList.contains("btn-problem")){
        let card = e.target.closest(".card");
        let id = card.dataset.id;

        let current = e.target.style.background;
        let isOn = current === "rgb(255, 214, 0)" || current === "#ffd600";

        let value = isOn ? 0 : 1;

        let fd = new FormData();
        fd.append("action","toggle");
        fd.append("id",id);
        fd.append("value",value);

        let r = await post(fd);
        if(r.ok){
            e.target.style.background = value ? "#ffd600" : "#666";
        }
    }
});

document.addEventListener("click", async e=>{
    if(e.target.classList.contains("btn-del")){
        if(!confirm("Excluir ônibus?")) return;

        let card = e.target.closest(".card");
        let id = card.dataset.id;

        let fd = new FormData();
        fd.append("action","delete");
        fd.append("id",id);

        let r = await post(fd);
        if(r.ok) card.remove();
    }
});

let editModal = document.getElementById("editModalBack");
let emsg = document.getElementById("edit_msg");

document.addEventListener("click", e=>{
    if(e.target.classList.contains("editBus")){
        let btn = e.target;

        edit_id.value = btn.dataset.id;
        edit_name.value = btn.dataset.name;
        edit_seats.value = btn.dataset.seats;

        emsg.style.display = "none";
        editModal.style.display = "flex";
    }
});

document.getElementById("edit_cancel").onclick = () => editModal.style.display="none";
editModal.onclick = e => { if(e.target === editModal) editModal.style.display="none"; };

document.getElementById("edit_save").onclick = async () => {
    let id = edit_id.value;
    let name = edit_name.value.trim();
    let seats = edit_seats.value;

    if(!name || seats <= 0){
        emsg.textContent = "Preencha nome e assentos.";
        emsg.style.display = "block";
        return;
    }

    let fd = new FormData();
    fd.append("action","edit");
    fd.append("id",id);
    fd.append("name",name);
    fd.append("seats",seats);

    let r = await post(fd);
    if(!r.ok){
        emsg.textContent = r.msg ?? "Erro ao editar.";
        emsg.style.display = "block";
        return;
    }

    let card = document.querySelector(`.card[data-id='${id}']`);
    card.querySelector(".name").textContent = name;
    card.querySelector(".label").textContent = seats + " assentos";

    editModal.style.display="none";
};
</script>

</body>
</html>