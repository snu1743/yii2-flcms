<div class="fl-action-tmp d-none">
    <div class="modal fade fl-action-parent" id="fl-action-modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Default Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="fl-action-modal btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade fl-action-parent" id="fl-action-modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Default Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="fl-action-modal btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <div id="fl-action-form-default">
        <form action="<?= $homePageHttps ?>apiadmin" class="fl-action-elem" data-action="action=sendForm">
            <div class="card-body"></div>
            <div class="card-footer d-none">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <div class="form-elements">
            <div class="form-group">
                <label>Select Multiple</label>
                <select multiple="" class="form-control">
                    <option>option 1</option>
                </select>
            </div>
            <div class="input-hidden d-none">
                <input type="hidden" class="form-control" id="input-hidden-" placeholder="">
            </div>
            <div class="form-group input-text">
                <label class="input-label" for="input-text-"></label>
                <input type="text" class="form-control" id="input-text-" placeholder="">
            </div>
            <div class="form-group form-label">
                <label class="input-label" for="input-text-"></label>
            </div>
            <div class="form-group input-email">
                <label class="input-label" for="exampleInputEmail1"></label>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="">
            </div>
            <div class="form-group input-password">
                <label class="input-label" for="exampleInputPassword1"></label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="">
            </div>
            <div class="form-group input-file">
                <label class="input-label" for="exampleInputFile">File input</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                    </div>
                </div>
            </div>
            <div class="form-check input-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="input-label" class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <div class="form-group form-elem-text">
                <div class="elem-text">text</div>
            </div>
        </div>
    </div>
    <div class="elem-table-list">
        <table class="table table-striped table-bordered">
            <thead class="d-none">
                <tr>
                    <th style="width: 10px">#</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td>1</td>
                    <td>1</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card grid-template-card">
        <div class="card-header">
            <h3 class="card-title app-title"></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="app-js-grid"></div>
        </div>
    </div>
    <div class="grid-template-paging">
        <div class="jsgrid-pager-container" style="display: block;">
            <div class="jsgrid-pager"></div>
            <div class="grid-template-paging-tmp" style="display: block;">
                <span class="jsgrid-pager-nav-button first-page"><a href="javascript:void(0);">First</a></span>
                <span class="jsgrid-pager-nav-button prev-page"><a href="javascript:void(0);">Prev</a></span>
                <span class="jsgrid-pager-nav-button prev-page-ellipsis"><a href="javascript:void(0);">...</a></span>
                <span class="jsgrid-pager-page jsgrid-pager-current-page">1</span>
                <span class="jsgrid-pager-page"><a href="javascript:void(0);">1</a></span>
                <span class="jsgrid-pager-nav-button next-page-ellipsis"><a href="javascript:void(0);">...</a></span>
                <span class="jsgrid-pager-nav-button next-page"><a href="javascript:void(0);">Next</a></span>
                <span class="jsgrid-pager-nav-button last-page"><a href="javascript:void(0);">Last</a></span> &nbsp;&nbsp; 1 of 1
            </div>
        </div>
    </div>
    <div class="card card-ace ace-template-card">
        <div class="card-header">
            <h3 class="card-title app-title"></h3>
        </div>
        <div class="card-body">
            <div class="container-code"></div>
        </div>
    </div>
    <div class="card card-json json-template-card fill-height-or-more">
        <div class="card-header">
            <h3 class="card-title app-title"></h3>
        </div>
        <div class="card-body">
            <div class="json-editor-container">
                <div class="row">
                    <div class="col-md-11">
                        <div class="jsoneditor-app"></div>
                    </div>
                    <div class="col-md-1 jsoneditor-app-menu">
                        <button type="button" class="btn btn-block btn-default menu-items-template d-none"></button>
                    </div>
                    <!--TODO-dual-editor реализовать двойной редактор-->
<!--                    <div class="col-md-5 d-none">-->
<!--                        <div class="jsoneditor-app-right"></div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>