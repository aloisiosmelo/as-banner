<div class="wrap">
    <h1 class="wp-heading-inline">Banners</h1>
    <a href="<?php print admin_url('admin.php?page=banner/add'); ?>" class="page-title-action">Add new</a>
    <hr class="wp-header-end">
    <h2 class="screen-reader-text">Lista de páginas</h2>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
        <tr>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a><span>Title</span><span class="sorting-indicator"></span></th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a><span>Shortcode</span><span class="sorting-indicator"></span></th>
            <th scope="col" id="date" class="manage-column column-date sortable asc"><a><span>Date</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a><span>Status</span><span class="sorting-indicator"></span></th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a><span>Actions</span><span class="sorting-indicator"></span></th>
        </tr>
        </thead>
        <tbody id="the-list">
        <?php if(!empty(getBanners())): ?>
            <?php foreach (getBanners() as $banner): ?>
                <tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry">
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Título">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <strong><a class="row-title" href="<?php print admin_url('admin.php?page=banner/edit&eId=') . $banner->id; ?>"><?=$banner->title?></a></strong>

                        <div class="row-actions">
                            <span class="edit">
                                <a href="<?php print admin_url('admin.php?page=banner/edit&eId=') . $banner->id; ?>">Edit</a> |
                            </span>
                            <span class="trash">
                                <a href="<?php print admin_url('admin.php?page=banner/delete&eId=') . $banner->id; ?>" class="submitdelete" onclick="return confirm('Delete entry?')">Delete</a> |
                            </span>
                        </div>

                        <button type="button" class="toggle-row"><span class="screen-reader-text">Mostrar mais detalhes</span></button>
                    </td>
                    <td class="title column-title has-row-actions column-primary page-title">
                        <code>[as_banner id=<?=$banner->id?>]</code>
                    </td>
                    <td class="date column-date" data-colname="Data"><?=$banner->created?></td>
                    <td class="date column-date" data-colname="Data"><?=($banner->published) ? 'Published' : 'Unpublished'; ?></td>
                    <td class="title column-title has-row-actions column-primary page-title">
                        <a href="<?php print admin_url('admin.php?page=banner/edit&eId=') . $banner->id; ?>">Edit</a> |
                        <a style="color: #a00" href="<?php print admin_url('admin.php?page=banner/delete&eId=') . $banner->id; ?>" class="submitdelete" onclick="return confirm('Delete entry?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else :?>
                <tr>
                    <td colspan="5">
                        No banner registered
                    </td>
                </tr>
        <?php endif; ?>
        </tbody>

    </table>
    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-bottom" class="screen-reader-text">Selecionar ação em massa</label>
            <select name="action2" id="bulk-action-selector-bottom">
                <option value="-1">Ações em massa</option>
                <option value="edit" class="hide-if-no-js">Editar</option>
                <option value="trash">Mover para lixeira</option>
            </select>
            <input type="submit" id="doaction2" class="button action" value="Aplicar">
        </div>
        <div class="alignleft actions"> </div>
        <div class="tablenav-pages one-page"><span class="displaying-num">2 itens</span> <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span><span class="screen-reader-text">Página atual</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 de <span class="total-pages">1</span></span></span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span></div>
        <br class="clear">
    </div>
</div>