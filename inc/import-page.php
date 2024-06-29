<?php
add_action('admin_menu', 'unlimited_andrenaline_admin_menu');
function unlimited_andrenaline_admin_menu()
{
    add_menu_page('Import Activities', 'Import Activities', 'manage_options', 'import-activities', 'unlimited_andrenaline_import_activities_page');
}

function unlimited_andrenaline_import_activities_page()
{
    ?>
    <div class="wrap">
        <h1>Import Activities</h1>
        <form method="post" action="">
            <input type="hidden" name="unlimited_andrenaline_import_activities" value="1">
            <button type="submit" class="button button-primary">Import Activities</button>
        </form>
        <?php
        if (isset($_POST['unlimited_andrenaline_import_activities'])) {
            unlimited_andrenaline_import_activities();
        }
        ?>
        <div id="import-progress" style="margin-top: 20px;">
            <p><strong>Activities Found:</strong> <span
                    id="activities-found"><?php echo get_option('activities_found', 0); ?></span></p>
            <p><strong>Activities Imported:</strong> <span
                    id="activities-imported"><?php echo get_option('activities_imported', 0); ?></span></p>
        </div>
    </div>
    <?php
}