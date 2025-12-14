<?php $errors = array(); ?>

<?php if (count($errors) > 0) : ?>
    <div class="error-messages mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <?php foreach ($errors as $error) : ?>
            <p class="mb-1"><?php echo $error; ?></p>
        <?php endforeach ?>
    </div>
<?php endif ?>