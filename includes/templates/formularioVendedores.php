<fieldset>
    <legend>Informacion general</legend>
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="vendedor[nombre]" placeholder="Nombre del vendedor" value="<?php echo sanitizar($vendedor->nombre); ?>">

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="vendedor[apellido]" placeholder="Apellido del vendedor" value="<?php echo sanitizar($vendedor->apellido); ?>">
</fieldset>

<fieldset>
    <legend>Informacion extra</legend>
    <label for="telefono">Telefono:</label>
    <input type="number" id="telefono" name="vendedor[telefono]" placeholder="Telefono del vendedor" value="<?php echo sanitizar($vendedor->telefono); ?>">
</fieldset>