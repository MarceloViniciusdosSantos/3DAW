<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">

<style>
body { margin:0; background:#000; color:#fff; font-family:Arial }
.container{ padding:20px }
.card-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
}
.card{
    background:#3a3a3a;
    padding:20px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.photo{
    width:70px; height:70px; border-radius:50%; object-fit:cover
}
.card-info{ flex:1; margin-left:15px }
.name{ font-size:18px; font-weight:bold }
.label{ font-size:14px; opacity:.8 }
.btns{
    display:flex;
    flex-direction:column;
    gap:5px;
}
.bedit,.bdel{
    border:none;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
    color:#000;
}
.bedit{ background:#ffd600 }
.bdel{ background:#ff3b3b }

.modal-back{
    position:fixed; inset:0;
    background:rgba(0,0,0,.75);
    display:none; justify-content:center; align-items:center;
}
.modal{
    width:420px; background:#1f1f1f;
    border-radius:12px; padding:20px;
}
.input{
    width:100%; padding:10px;
    margin-bottom:12px; border:none; border-radius:8px;
}
.btn-save{ background:#ffd600 }
.btn-cancel{ background:#333; color:#fff }
.msg{ color:#ffd600; display:none; margin-top:10px }
</style>
</head>

<body>
<div class="container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Funcionários</h2>
        <button id="openAdd" style="background:#5c4600; color:#000; padding:10px 15px; border-radius:10px; border:none; cursor:pointer;">
            Adicionar motorista
        </button>
    </div>

    <div class="card-grid" id="driverGrid">
        <?php foreach ($drivers as $d): ?>
        <div class="card" data-id="<?= $d['id'] ?>">
            <img src="<?= $d['photo'] ?>" class="photo">

            <div class="card-info">
                <div class="name"><?= htmlspecialchars($d['name']) ?></div>
                <div class="label"><?= (int)$d['years_experience'] ?> anos dirigindo</div>
                <div class="label">Ônibus: <?= $d['bus_name'] ?: "Nenhum" ?></div>
            </div>

            <div class="btns">
                <button class="bedit">Editar</button>
                <button class="bdel">Excluir</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<div class="modal-back" id="modalBack">
<div class="modal">

    <h3 id="modalTitle">Adicionar Motorista</h3>

    <input class="input" id="mName" placeholder="Nome">
    <input class="input" id="mExp" type="number" min="0" placeholder="Anos de experiência">
    <input class="input" id="mPhone" placeholder="Telefone">
    <input class="input" id="mPhoto" type="file" accept="image/*">

    <select class="input" id="mBus">
        <option value="">Sem ônibus</option>
        <?php foreach ($buses as $b): ?>
            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" id="editID">

    <div style="display:flex; justify-content:flex-end; gap:10px;">
        <button class="btn-cancel" id="cancelAdd">Cancelar</button>
        <button class="btn-save" id="saveAdd">Salvar</button>
    </div>

    <div class="msg" id="modalMsg"></div>

</div>
</div>

<script>
function postFD(fd){
    return fetch(location.href,{method:"POST",body:fd}).then(r=>r.json());
}

let modalBack = document.getElementById("modalBack");
let modalTitle = document.getElementById("modalTitle");
let modalMsg = document.getElementById("modalMsg");
let editID = document.getElementById("editID");

document.getElementById("openAdd").onclick = () => {
    modalTitle.textContent = "Adicionar Motorista";
    editID.value = "";
    mName.value = "";
    mExp.value = "";
    mPhone.value = "";
    mBus.value = "";
    mPhoto.value = "";
    modalMsg.style.display = "none";
    modalBack.style.display = "flex";
};

document.getElementById("cancelAdd").onclick = () => modalBack.style.display="none";

modalBack.addEventListener("click", e=>{
    if(e.target === modalBack) modalBack.style.display="none";
});

document.getElementById("saveAdd").onclick = async ()=>{

    let id = editID.value;
    let name = mName.value.trim();
    let exp = mExp.value;
    let phone = mPhone.value.trim();
    let bus  = mBus.value;
    let photo = mPhoto.files[0];

    if(!name || exp==="" || exp<0){
        modalMsg.textContent = "Preencha nome e anos de experiência.";
        modalMsg.style.display="block";
        return;
    }

    let fd = new FormData();
    fd.append("name",name);
    fd.append("years_experience",exp);
    fd.append("phone",phone);
    fd.append("bus_id",bus);

    if(id){ 
        fd.append("action","edit_driver");
        fd.append("id",id);
        if(photo) fd.append("photo",photo);
    } else {
        if(!photo){
            modalMsg.textContent = "A foto é obrigatória.";
            modalMsg.style.display="block";
            return;
        }
        fd.append("action","add_driver");
        fd.append("photo",photo);
    }

    let r = await postFD(fd);

    if(!r.ok){
        modalMsg.textContent = r.msg;
        modalMsg.style.display = "block";
        return;
    }

    if(!id){
        let grid = document.getElementById("driverGrid");
        let card = document.createElement("div");
        card.className = "card";
        card.setAttribute("data-id", r.id);

        card.innerHTML = `
            <img src="${r.photo_path}" class="photo">
            <div class="card-info">
                <div class="name">${name}</div>
                <div class="label">${exp} anos dirigindo</div>
                <div class="label">Ônibus: ${r.bus_name || "Nenhum"}</div>
            </div>
            <div class="btns">
                <button class="bedit">Editar</button>
                <button class="bdel">Excluir</button>
            </div>
        `;
        grid.insertBefore(card, grid.firstChild);

    } else {
        let card = document.querySelector(`.card[data-id="${id}"]`);

        if(r.photo_path){
            card.querySelector("img").src = r.photo_path;
        }

        card.querySelector(".name").textContent = name;
        card.querySelectorAll(".label")[0].textContent = `${exp} anos dirigindo`;
        card.querySelectorAll(".label")[1].textContent = `Ônibus: ${r.bus_name || "Nenhum"}`;
    }

    modalBack.style.display = "none";
};

document.addEventListener("click", e=>{
    if(e.target.classList.contains("bedit")){
        let card = e.target.closest(".card");
        let id = card.dataset.id;

        editID.value = id;
        modalTitle.textContent = "Editar Motorista";

        mName.value = card.querySelector(".name").textContent;
        let labels = card.querySelectorAll(".label");
        mExp.value = parseInt(labels[0].textContent);
        mPhone.value = "";
        mBus.value = "";

        modalMsg.style.display = "none";
        mPhoto.value = "";
        modalBack.style.display = "flex";
    }
});

document.addEventListener("click", async e=>{
    if(e.target.classList.contains("bdel")){
        if(!confirm("Excluir motorista?")) return;

        let card = e.target.closest(".card");
        let id = card.dataset.id;

        let fd = new FormData();
        fd.append("action","delete_driver");
        fd.append("id",id);

        let r = await postFD(fd);

        if(r.ok){
            card.remove();
        }
    }
});
</script>

</body>
</html>