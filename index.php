<?php
/**
 * @Created by          : Drajat Hasan (drajathasan20@gmail.com)
 * @Date                : 10/05/2022 16:40
 * @File name           : index.php
 */

defined('INDEX_AUTH') OR die('Direct access not allowed!');

// IP based access limitation
require LIB . 'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-system');
// start the session
require SB . 'admin/default/session.inc.php';
require SIMBIO . 'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO . 'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO . 'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO . 'simbio_DB/datagrid/simbio_dbgrid.inc.php';

// privileges checking
$can_read = utility::havePrivilege('system', 'r');

if (!$can_read) {
    die('<div class="errorBox">' . __('You are not authorized to view this section') . '</div>');
}

function httpQuery($query = [])
{
    return http_build_query(array_unique(array_merge($_GET, $query)));
}

$data = config('disqus_url');
if (isset($_POST['saveData']))
{
    if (is_null($data))
    {
        \SLiMS\DB::getInstance()->prepare('insert into setting set setting_name = ?, setting_value = ?')->execute(['3rd_party_comment', serialize(['active' => true])]);
        \SLiMS\DB::getInstance()->prepare('insert into setting set setting_name = ?, setting_value = ?')->execute(['disqus_url', serialize([$_POST['disqusurl']]) ]);
    }
    else
    {
        \SLiMS\DB::getInstance()->prepare('update setting set setting_value = ? where setting_name = ?')->execute([serialize([$_POST['disqusurl']]), 'disqus_url']);
    }
    utility::jsToastr(__('Success'), 'Berhasil menambah data', 'success');
    exit;
}

?>

<div class="menuBox">
    <div class="menuBoxInner printIcon">
        <div class="per_title">
            <h2><?php echo __('Disqus Configuration'); ?></h2>
        </div>
    </div>
</div>

<?php
// create new instance
$form = new simbio_form_table_AJAX('mainForm', $_SERVER['PHP_SELF'] . '?' . httpQuery(), 'post');

// form table attributes
$form->table_attr = 'id="dataList" class="s-table table"';
$form->table_header_attr = 'class="alterCell font-weight-bold"';
$form->table_content_attr = 'class="alterCell2"';
$form->submit_button_attr = 'name="saveData" value="'.__('Save Settings').'" class="btn btn-default"';

$html = '<input type="form-control" name="disqusurl" style="width: 60%;" class="form-control" value="' . ($data[0]??'') . '"/>';
$html .= '<small>Cara mendapatkan/How to get Disqus URL : <a href="https://github.com/drajathasan/slims-disqus-plugin/blob/main/README.md">Dokumentasi plugin</a></small>';
$form->addAnything(__('Disqus URL'),  $html);

// print out the object
echo $form->printOut();