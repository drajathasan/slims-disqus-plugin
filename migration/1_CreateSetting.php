<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-05-10 11:33:52
 * @modify date 2022-05-10 13:10:05
 * @license GPLv3
 * @desc [description]
 */

class CreateSetting extends \SLiMS\Migration\Migration
{
    function up()
    {
        $this->copyFile();
        \SLiMS\DB::getInstance()->prepare('insert into setting set setting_name = ?, setting_value = ?')->execute(['3rd_party_comment', serialize(['active' => true])]);
    }

    function down()
    {
        $this->copyFile('rollback');
        \SLiMS\DB::getInstance()->prepare('delete from setting where setting_name = ?')->execute(['3rd_party_comment']);
        \SLiMS\DB::getInstance()->prepare('delete from setting where setting_name = ?')->execute(['disqus_url']);
    }

    private function copyFile($state = 'new')
    {
        if (ENVIRONMENT === 'development') return;
        
        if (ltrim(SENAYAN_VERSION_TAG, 'v') <= '9.4.2')
        {
            if ($state === 'new')
            {
                // backup current files
                copy(LIB . 'comment.inc.php', LIB . 'comment.inc.orig.php');
                
                // remove current file
                unlink(LIB . 'comment.inc.php');

                // Copy new file into lib/
                copy(__DIR__ . '/../comment.inc.php', LIB . 'comment.inc.php');
            }
            else
            {
                // remove current file
                unlink(LIB . 'comment.inc.php');
                
                // backup current files
                copy(LIB . 'comment.inc.orig.php', LIB . 'comment.inc.php');
            }
        }
    }
}