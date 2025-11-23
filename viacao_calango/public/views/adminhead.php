<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');
$assets_path = str_replace('/public', '', $base_path);
?>

<div class="admin-header">
    <div class="admin-back">
        ←
    </div>
    <div class="admin-user">
        admin
    </div>
</div>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px;
    background: #000;
    color: #fff;
    font-family: Arial, sans-serif;
    font-size: 18px;
}

.admin-back {
    cursor: pointer;
    font-size: 22px;
    user-select: none;
}

.admin-back:hover {
    opacity: 0.7;
}

.admin-user {
    font-size: 18px;
    font-weight: bold;
    user-select: none;
}
</style>

<script>
document.querySelector('.admin-back').addEventListener('click', () => {
    window.history.back();
});
</script>
