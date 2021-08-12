<?php

use fl\cms\helpers\encryption\FLHashEncrypStatic as FLHashEncryp;

?>
<section class="fl-page-content content">
    <div class="fl-entity-common-data d-none" data-edit-mod="1"></div>
    <!--                        <textarea id="fl-page-editor-content" class="d-none">--><?//= $placeholders['content']; ?><!--</textarea>-->
    <?php
    $params = json_encode(
        []
    );
    ?>
    <div class="fl-app auto-init"
         data-string__entity="page"
         data-string__action_name="edit"
         data-string__common.app="ace_edit"
         data-string__content=""
         data-object__local_data.properties='<?php
         $content=[[
             'params' => FLHashEncryp::encrypt($params),
             'content' => $body,
         ]];
         echo json_encode($content);
         ?>'
    >
</section>
