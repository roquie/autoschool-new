<?=HTML::style('adm/css/settings.css')?>
<?=HTML::script('adm/js/upload.js')?>
<?=HTML::style('global/css/view_doc.css')?>
<?=HTML::script('global/js/viewdoc.js')?>
<style>
    .b-button {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        position: relative;
        overflow: hidden;
    }
    .b-button__input {
        cursor: pointer;
        opacity: 0;
        filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
        top: 0px;
        right: -50px;
        font-size: 50px;
        position: absolute;
    }
    .b-button .progress-bar {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: .5;
        position: absolute;
    }
    .b-button .progress-bar .bar {
        width: 0;
        top: 0;
        left: 0;
        bottom: 0;
        position: absolute;
        background-color: #fff;
    }
</style>

<div class="container">

    <h1><small>Настройки</small></h1>

    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li><a href="<?=URL::site('admin/settings/')?>">Главная страница</a></li>
            <li><a href="<?=URL::site('admin/settings/administrators')?>">Администраторы</a></li>
            <li class="active"><a href="<?=URL::site('admin/settings/upload')?>">Замена шаблонов</a></li>
            <li><a href="<?=URL::site('admin/settings/smtp')?>">SMTP</a></li>
            <li><a href="<?=URL::site('admin/settings/sync')?>">Синх.</a></li>
            <li><a href="<?=URL::site('admin/settings/backup')?>">Резервные копии</a></li>
            <li><a href="<?=URL::site('admin/settings/notification')?>">Уведомления</a></li>
        </ul>
        <div class="tab-content">

            <?=View::factory('errors/msg')?>

            <div class="row" style="overflow-x: hidden">
            <div class="span8" >
                <div class="well" style="height: 300px;">
                    <h5 class="header_block">Файлы сайта</h5>
                    <style type="text/css">
                        .btn-upl_tmpl {
                            padding: 2px 10px 2px 10px;
                        }
                    </style>
                    <table id="upload_templ_table" class="table table_files">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Выбрать</th>
                            <th>Тип</th>
                            <th>Скачивание/Просмотр</th>
                        </tr>
                        </thead>

                        <tbody style="height: 216px">
                            <?foreach ($files as $file):?>
                                <tr>
                                    <td style="line-height: 26px"><?=$file->id?></td>
                                    <td><input type="checkbox" value="<?=$file->id?>" name="rd_file" /></td>
                                    <td rel="tooltip" title="<?=$file->filename?>" style="line-height: 26px"><?=$file->desc?></td>
                                    <td>
                                        <div class="btn-group" style="height: 25px">
                                            <?if($file->type == Controller_Admin_Settings::UPLOAD_TYPE_TEMPLATE):?>
                                                <a href="<?=URL::site('tdownload/template/'.time().'@!'.$file->path.$file->filename)?>" rel="tooltip" title="Загрузить" class="btn btn-success btn-upl_tmpl"><i class="icon-download"></i></a>
                                                <a href="#view_doc_modal" data-url="<?=URL::site('admin/files/look/other?url='.URL::site('tdownload/template/'.time().'@!'.$file->path.$file->filename))?>" data-type="statement" data-toggle="modal" rel="tooltip" title="Открыть" class="btn btn-info btn-upl_tmpl view_doc_createtmpfile"><i class="icon-eye-open"></i></a>
                                            <?else:?>
                                                <a href="<?=URL::site('tdownload/file/'.time().'@!'.$file->filename)?>" rel="tooltip" title="Загрузить" class="btn btn-success btn-upl_tmpl"><i class="icon-download"></i></a>
                                                <?if(strtolower(pathinfo($file->filename, PATHINFO_EXTENSION)) !== 'pdf'):?>
                                                    <a href="#view_doc_modal" data-url="<?=URL::site('admin/files/look/other?url='.URL::site('tdownload/file/'.time().'@!'.$file->filename))?>" data-type="statement" data-toggle="modal" rel="tooltip" title="Открыть" class="btn btn-info btn-upl_tmpl view_doc_createtmpfile"><i class="icon-eye-open"></i></a>
                                                <?endif?>
                                            <?endif?>
                                        </div>
                                    </td>
                                </tr>
                            <?endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4">
                <div class="well" style="height: 300px;">
                    <h5 class="header_block">Загрузить</h5>
                    <p style="text-align: center">Для замены шаблона выберите файл в таблице слева. <br/><br/>При необходимости можно скачать или просмотреть существующие.</p>
                    <div class="b-button js-fileapi-wrapper" style="margin-bottom: 10px; margin-top: 40px; margin-left: 60px">
                        <form action="<?=Route::url('admin', array('controller'=>'settings', 'action'=>'upload'))?>" method="post" enctype="multipart/form-data">
                            <div class="browse">
                            <? if (!isset($data)) : ?>
                                <a class="b-button__text btn btn-success" href="#">Загрузить файл</a>
                                <input name="files" class="b-button__input" type="file"/>
                                <input type="hidden" name="csrf" value="<?=Security::token()?>"/>
                                <input type="hidden" id="type_file" name="type_file"/>
                            <? else : ?>
                                <input type="submit" value="Загрузить"/>
                            <? endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

</div>


<?=View::factory('view_doc')->render()?>