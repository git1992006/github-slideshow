<?php
$isEditing = isset($note);
$noteData = $note ?? [
    'title' => $_POST['title'] ?? '',
    'content' => $_POST['content'] ?? '',
    'category_id' => $_POST['category_id'] ?? '',
    'priority' => $_POST['priority'] ?? 'Medium',
    'status' => $_POST['status'] ?? 'Active',
    'is_favorite' => isset($_POST['is_favorite']) ? 1 : 0,
    'is_pinned' => isset($_POST['is_pinned']) ? 1 : 0,
    'color_label' => $_POST['color_label'] ?? '#7c3aed',
];
$selectedTags = $selectedTags ?? array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));
$selectedLinkedNotes = $selectedLinkedNotes ?? array_map('intval', $_POST['linked_notes'] ?? []);
?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <span class="section-kicker"><?= $isEditing ? 'Edit note' : 'New note' ?></span>
        <h1 class="fw-bold mb-1"><?= $isEditing ? 'Refine your saved knowledge' : 'Capture a new idea' ?></h1>
        <p class="text-secondary mb-0">Keep your vault structured with metadata, tags, and linked notes.</p>
    </div>
    <?php if ($isEditing): ?><a href="view_note.php?id=<?= (int) $noteId ?>" class="btn btn-outline-secondary rounded-pill">Back to Note</a><?php endif; ?>
</div>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="row g-4">
    <div class="col-lg-8">
        <div class="panel-card">
            <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control form-control-lg" value="<?= h($noteData['title']) ?>" required></div>
            <div><label class="form-label">Description / Content</label><textarea name="content" class="form-control" rows="12" required><?= h($noteData['content']) ?></textarea></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card mb-4">
            <div class="mb-3"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="">Select category</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= (int) $noteData['category_id'] === (int) $category['id'] ? 'selected' : '' ?>><?= h($category['name']) ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label class="form-label">Tags</label><input type="text" name="tags" class="form-control" value="<?= h(implode(', ', $selectedTags)) ?>" placeholder="productivity, startup, coding"></div>
            <div class="row g-3">
                <div class="col-6"><label class="form-label">Priority</label><select name="priority" class="form-select"><?php foreach (['Low', 'Medium', 'High'] as $level): ?><option value="<?= $level ?>" <?= $noteData['priority'] === $level ? 'selected' : '' ?>><?= $level ?></option><?php endforeach; ?></select></div>
                <div class="col-6"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach (['Active', 'Archived'] as $state): ?><option value="<?= $state ?>" <?= $noteData['status'] === $state ? 'selected' : '' ?>><?= $state ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="mt-3"><label class="form-label">Color Label</label><input type="color" name="color_label" class="form-control form-control-color" value="<?= h($noteData['color_label']) ?>"></div>
            <div class="mt-3">
                <label class="form-label">Linked Notes</label>
                <select name="linked_notes[]" class="form-select" multiple size="6">
                    <?php foreach ($existingNotes as $existing): ?><option value="<?= (int) $existing['id'] ?>" <?= in_array((int) $existing['id'], $selectedLinkedNotes, true) ? 'selected' : '' ?>><?= h($existing['title']) ?></option><?php endforeach; ?>
                </select>
                <div class="form-text">Hold Ctrl/Cmd to select multiple related notes.</div>
            </div>
            <div class="d-flex flex-column gap-2 mt-3">
                <div class="form-check"><input class="form-check-input" type="checkbox" name="is_favorite" id="favoriteCheck" <?= (int) $noteData['is_favorite'] === 1 ? 'checked' : '' ?>><label class="form-check-label" for="favoriteCheck">Mark as favorite</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="is_pinned" id="pinnedCheck" <?= (int) $noteData['is_pinned'] === 1 ? 'checked' : '' ?>><label class="form-check-label" for="pinnedCheck">Pin this note</label></div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill"><?= $isEditing ? 'Save Changes' : 'Create Note' ?></button>
            <a href="notes.php" class="btn btn-outline-secondary rounded-pill">Cancel</a>
        </div>
    </div>
</form>
