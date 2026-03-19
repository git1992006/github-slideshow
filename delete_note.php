<?php
require_once 'includes/functions.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$noteId = (int) ($_GET['id'] ?? 0);
$statement = $pdo->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?');
$statement->execute([$noteId, $userId]);
setFlash('flash_success', 'Note deleted permanently.');
header('Location: notes.php');
exit;
