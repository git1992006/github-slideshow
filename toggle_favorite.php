<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$noteId = (int) ($_GET['id'] ?? 0);
$get = $pdo->prepare('SELECT is_favorite FROM notes WHERE id = ? AND user_id = ? LIMIT 1');
$get->execute([$noteId, $userId]);
$note = $get->fetch();
if ($note) {
    $newState = (int) $note['is_favorite'] === 1 ? 0 : 1;
    $update = $pdo->prepare('UPDATE notes SET is_favorite = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?');
    $update->execute([$newState, $noteId, $userId]);
    setFlash('flash_success', $newState ? 'Note added to favorites.' : 'Note removed from favorites.');
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'notes.php'));
exit;
