<div id="view-file" class="modal modal-view-file">
    <div class="modal-content">
        <i class="material-icons large blue lighten-1 file-icon">description</i>
        <h2 class="file-name"></h2>
        <!-- <p class="id"></p> -->
        <p class="description">File description and other necessary details.</p>
        <p class="date"></p>
    </div>
    <div class="btn-group-horiz">
        <button id="delete-button" class="btn btn-square waves-effect waves-light red crud-file"> <i class="material-icons">delete</i></button>
        <button id="update-button" class="btn btn-square waves-effect waves-light crud-file right-i"> <i class="material-icons">edit</i></button>
        <a id="download-button" class="btn btn-square waves-effect waves-light"> <i class="material-icons">file_download</i></a>
    </div>
</div>

<div id="delete-conf" class="modal delete-modal">
    <div class="modal-content">
        <h5></h5>
        <div class="btn-group-horiz">
            <button class="btn waves-effect waves-light grey darken-2 modal-action modal-close">Cancel <i class="right material-icons">cancel</i></button>
            <form id="delete_file" action="" method="POST">
                {{csrf_field()}}
                {{METHOD_FIELD('DELETE')}}
                <button id="delete-btn" class="btn waves-effect waves-light red confirmed_del">Delete <i class="right material-icons">delete</i></button>
            </form>
        </div>
    </div>
</div>

<div id="upload" class="modal form-modal">
    <div class="modal-content">
        <form id="upload_form" action="" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <h2 class="modal-header">Upload</h2>
            <div class="file-input-desc">
                <label class="input-file">
                    <span class="waves-effect waves-light light-blue"><i class="material-icons large">note_add</i></span>
                    <input type="file" id="filename" name="filename" required="required" accept=".docx, .doc, .xls, . xlsx, .ods, .odt, .pdf, .jpeg, .jpg, .png, .ppt, .pptx" />
                </label>
                <div class="file-input-details">
                    <span class="name truncate">Select a file</span>
                    <textarea id="description" name="description" required="required" placeholder="Description of the file"></textarea>
                </div>
            </div>
            <div class="tag-input">
                <div class="add-tag-form">
                    <input type="text" name="add_tag_name" id="add_tag_name" placeholder="Tag">
                    <button type="button" class="btn btn-square waves-effect waves-light" id="add-tag-btn"><i class="material-icons">local_offer</i></button>
                    <ul id="tag-suggest" class="tag-suggest">
                        @forelse(Auth::user()->availableTags() as $tag)
                        <li><i class="material-icons red-text left">local_offer</i>{{$tag->name}}</li>
                        @empty
                        <li class="none">No suggested tags</li>
                        @endforelse
                    </ul>
                </div>
                <div class="selected-tag-container">
                    <p id="tag-name" class="tag-name"></p>
                    <span id="remove-tag" class="remove-tag">&times;<span/>
                </div>
                <input type="hidden" id="tag-id" name="tag-id" value=""/>
            </div>
            <div class="footer">
                <div class="input-field public">
                    <input type="checkbox" name="public" id="public">
                    <label for="public">
                        @if(Auth::user()->isAdmin())
                            Share to other users
                        @else
                            Make this document public
                        @endif
                    </label>
                </div>
                <div class="input-field">
                    <button class="btn waves-effect waves-light light-green submit" id="submit" type="submit"><i class="material-icons right">file_upload</i>upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="upload_message" class="modal">
    <div class="modal-content">
        <h4></h4>
        <p></p>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" class="modal-action modal-close waves-effect btn-flat light-green center-block">OK!</a>
    </div>
</div>

<!--- FOR UPDATE -->
<div id="update" class="modal form-modal">
    <div class="modal-content">
        <form name="update_form" action="" method="PATCH" role="form" enctype="x-www-url-formurlencoded" id="update_form">
            <!-- change to /file/$file->id -->
            {{ csrf_field() }}
            {{METHOD_FIELD('PUT')}}
            <h2 class="modal-header">Upload Revision</h2>
            <div class="file-input-desc">
                <label class="input-file">
                    <span class="waves-effect waves-light light-blue"><i class="material-icons large">note_add</i></span>
                    <!-- <input type="file" id="filename" name="filename" required="required" accept=".docx, .doc, .xls, . xlsx, .ods, .odt, .pdf, .jpeg, .jpg, .png, .ppt, .pptx" /> -->
                    <input type="file" id="edit_file" name="edit_filename" required="required" accept=".docx, .doc, .xls, . xlsx, .ods, .odt, .pdf, .jpeg, .jpg, .png, .ppt" />
                </label>
                <div class="file-input-details">
                    <span class="name truncate">Select a file</span>
                    <!-- <textarea id="description" name="description" required="required" placeholder="Description of the file"></textarea> -->
                    <textarea id="edit_description" name="edit_description" required="required" placeholder="Description of the file"></textarea>
                </div>
            </div>
            <!-- <label class="input-file"> -->
                <!-- <span class="name"></span> -->
            <!-- </label> -->
            <div class="tag-input">
                <div class="add-tag-form">
                    <input type="text" name="edit_tag_name" id="edit_tag_name" placeholder="Tag">
                    <button type="button" class="btn btn-square waves-effect waves-light" id="edit-tag-btn"><i class="material-icons">local_offer</i></button>
                    <ul id="edit-tag-suggest" class="tag-suggest">
                        @forelse(Auth::user()->availableTags() as $tag)
                        <li><i class="material-icons red-text left">local_offer</i>{{$tag->name}}</li>
                        @empty
                        <li><i class="material-icons red-text left">local_offer</i>No suggested tags</li>
                        @endforelse
                    </ul>
                </div>
                <div class="selected-tag-container">
                    <p id="edited-tag-name" class="tag-name">Test tag Test tag Test tag Test tag Test tag Test tag Test tag Test tag</p>
                    <span id="edit-remove-tag" class="remove-tag">&times;<span/>
                </div>
                <input type="hidden" id="tag-id" name="tag-id" value=""/>
            </div>
            <div class="footer">
                <div class="input-field public">
                    <input type="checkbox" name="edit_public" id="edit_public" />
                    <label for="edit_public">
                        @if(Auth::user()->isAdmin())
                            Shared to other users
                        @else
                            Public Document
                        @endif</label>
                </div>
                <div class="input-field">
                    <button class="btn light-green waves-effect submit" id="update_button" type="submit">Upload Revision<i class="material-icons right">file_upload</i></button>
                </div>
            </div>
        </form>
    </div>
</div>