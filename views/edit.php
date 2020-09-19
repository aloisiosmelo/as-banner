<?php defined('ABSPATH') or die('No script kiddies please!'); wp_enqueue_media(); ?>
<div class="wrap">
    <div id="col-container" class="wp-clearfix">
        <form id="editBanner" method="post" action="<?php print admin_url('admin.php?page=banner/edit&eId=') . $banner['id']; ?>" class="validate">
            <div id="col-left">
                <div class="col-wrap">
                    <div class="form-wrap">
                        <h1>Edit - Banner</h1>

                            <div class="form-field form-required term-name-wrap">
                                <label for="banner[title]">Title</label>
                                <input name="banner[title]" id="banner-title" type="text" value="<?= $banner['title']?>" size="255" aria-required="true">
                            </div>

                            <div class="form-field">
                                <label for="banner[published]">Status</label>
                                <select name="banner[published]" id="published" >
                                    <option value="0">Unpublished</option>
                                    <option value="1">Published</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="banner[created]">Created</label>
                                <input name="banner[created]" id="banner-created" type="text" disabled value="<?= $banner['created']?>" size="40">
                            </div>

                            <p class="submit">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save"><span class="spinner"></span>
                            </p>

                    </div>
                </div>
            </div>

            <div id="col-right">

                <table id="table_itens" class="wp-list-table widefat fixed striped table-view-list pages">
                    <?php if(!empty($banner_itens)):?>

                        <?php foreach ($banner_itens as $key => $item): ?>
                            <tr class="table-row">
                                <td>
                                    <div class="form-field form-required term-name-wrap">
                                        <label for="item[<?=$key?>][title]">Title</label>
                                        <input name="item[<?=$key?>][title]" type="text" value="<?= $item['title']?>" size="250" aria-required="true">
                                        <input name="item[<?=$key?>][id]" type="hidden" value="<?= $item['id'] ?>">
                                        <input name="item[<?=$key?>][banner_id]" type="hidden" value="<?= $item['banner_id'] ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-field form-required term-name-wrap">
                                        <label for="item[<?=$key?>][description]">Description</label>
                                        <textarea name="item[<?=$key?>][description]" rows="3" cols="50"><?= $item['description']?></textarea>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-field form-required term-name-wrap">
                                        <label for="image-preview">Image</label>
                                        <br>
                                        <input name="item[<?=$key?>][image_attachment_id]" type="hidden" value="<?= $item['image_attachment_id'] ?>">
                                        <img id='image-preview' src='<?php echo wp_get_attachment_url($item['image_attachment_id']); ?>' width='200'>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <td colspan="3">
                            No attachments
                        </td>
                    <?php endif;?>

                </table>

                <br>
                <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />

            </div><!-- /col-right -->
        </form>
    </div>
</div>

<script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
        $( '#published' ).val('<?=$banner['published']?>');
        var banner_id = <?=$banner['id'] ?>;
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
                tr += '<input name="item['+attachmentsCount+'][banner_id]" type="hidden" value="'+banner_id+'">';
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