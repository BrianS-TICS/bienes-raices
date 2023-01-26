<fieldset>
    <legend>Informacion general</legend>
    <label for="titulo">Titulo:</label>
    <input type="text" id="titulo" name="propiedad[titulo]" placeholder="Titulo de propiedad" value="<?php echo sanitizar($propiedad->titulo); ?>">

    <label for="precio">Precio:</label>
    <input type="number" maxlength="10" minlength="1" id="precio" placeholder="Precio de propiedad" name="propiedad[precio]" value="<?php echo sanitizar($propiedad->precio); ?>">

    <label for="imagen">Imagen:</label>
    <input type="file" accept="image/jpeg, image/png" id="imagen" name="propiedad[imagen]" name="imagen">
    <?php if ($propiedad->imagen) : ?>
        <img src="/imagenes/<?php echo $propiedad->imagen ?>" class="imagen-small" alt="imagen de propiedad">
    <?php endif; ?>

    <label for="descripcion">Descripcion</label>
    <textarea name="propiedad[descripcion]" id="descripcion" cols="30" rows="10"><?php echo sanitizar($propiedad->descripcion); ?></textarea>

</fieldset>

<fieldset>
    <legend>Informacion de la propiedad:</legend>

    <label for="habitaciones">Habitaciones:</label>
    <input type="number" id="habitaciones" placeholder="Ejemplo: 2" min="1" max="9" name="propiedad[habitaciones]" value="<?php echo sanitizar($propiedad->habitaciones); ?>">

    <label for="wc">Baños:</label>
    <input name="propiedad[wc]" type="number" id="wc" placeholder="Ejemplo: 2" min="1" max="9" value="<?php echo sanitizar($propiedad->wc); ?>">

    <label for="estacionamiento">Estacionamientos:</label>
    <input type="number" id="estacionamiento" placeholder="Ejemplo: 2" min="1" max="9" name="propiedad[estacionamiento]" value="<?php echo  sanitizar($propiedad->estacionamiento); ?>">

</fieldset>

<fieldset>
    <legend>Vendedor</legend>
    <label for="vendedor">Vendedor</label>
    <select name="propiedad[$vendedorId]" id="vendedor">
        <option value=""> -- Seleccione -- </option>
        <?php foreach ($vendedores as $vendedor) { ?>
            <option <?php echo $propiedad->vendedorId === $vendedor->id ? 'selected' : ''; ?> value="<?php echo sanitizar($vendedor->id) ?>"> <?php echo sanitizar($vendedor->nombre) . ' ' . sanitizar($vendedor->apellido); ?></option>
        <?php  } ?>
    </select>
</fieldset>