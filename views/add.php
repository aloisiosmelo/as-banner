<?php defined('ABSPATH') or die('No script kiddies please!'); wp_enqueue_media(); ?>
<div class="wrap">
    <div id="col-container" class="wp-clearfix">
        <form id="editBanner" method="post" action="<?php print admin_url('admin.php?page=banner/add')?>" class="validate">
            <div id="col-left">
                <div class="col-wrap">
                    <div class="form-wrap">
                        <h1>Add - Banner</h1>

                        <div class="form-field form-required term-name-wrap">
                            <label for="banner[title]">Title</label>
                            <input name="banner[title]" id="banner-title" type="text" size="255" aria-required="true">
                        </div>

                        <div class="form-field">
                            <label for="banner[published]">Status</label>
                            <select name="banner[published]" id="published" >
                                <option value="0">Unpublished</option>
                                <option value="1">Published</option>
                            </select>
                        </div>

                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save"><span class="spinner"></span>
                        </p>

                    </div>
                </div>
            </div>

            <div id="col-right">

                <table id="table_itens" class="wp-list-table widefat fixed striped table-view-list pages">
                    <tr class="table-row">
                        <td colspan="3">
                            No attachments
                        </td>
                    </tr>
                </table>

                <br>
                <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />

            </div><!-- /col-right -->
        </form>
    </div>
</div>

<script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
        var attachmentsCount = $(".table-row").length;

        // Uploading files
        var file_frame;
        jQuery('#upload_image_button').on('click', function( event ){
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                // Open frame
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false // Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader

                var attachment = file_frame.state().get('selection').first().toJSON();

                var tr = '';

                tr += '<tr>';

                // tittle td
                tr += '<td><div class="form-field form-required term-name-wrap">';
                tr += '<label for="item['+attachmentsCount+'][title]">Title</label>';
                tr += '<input name="item['+attachmentsCount+'][title]" type="text" value="" size="250" aria-required="true">';
                tr += '<input name="item['+attachmentsCount+'][id]" type="hidden" value="">';
                tr += '</div></td>';

                // descriptions td
                tr += '<td><div class="form-field form-required term-name-wrap">';
                tr += '<label for="item['+attachmentsCount+'][description]">Description</label>';
                tr += '<textarea name="item['+attachmentsCount+'][description]" rows="3" cols="50"></textarea>';
                tr += '</div></td>';

                // image td
                tr += '<td><div class="form-field form-required term-name-wrap">';
                tr += '<label for="item['+attachmentsCount+'][image]">Title</label>';
                tr += '<br>';
                tr += '<input name="item['+attachmentsCount+'][image_attachment_id]" type="hidden" value="'+attachment.id+'">';
                tr += '<img id="image-preview" src="'+attachment.url+'" width="200">';
                tr += '</div></td>';

                tr += '</tr>';

                $('#table_itens tr:last').after(tr);
                attachmentsCount++;

            });
            // Finally, open the modal
            file_frame.open();
        });

    });
</script>

<style>
    .image-preview-wrapper > img {
        width: 200px;
    }
</style>