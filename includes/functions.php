<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connect.php';

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['flash_error'] = 'Please log in to continue.';
        header('Location: login.php');
        exit;
    }
}

function redirectIfLoggedIn(): void
{
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit;
    }
}

function setFlash(string $key, string $message): void
{
    $_SESSION[$key] = $message;
}

function getFlash(string $key): ?string
{
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $message;
}

function currentUser(PDO $pdo): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    $statement = $pdo->prepare('SELECT id, full_name, email, created_at FROM users WHERE id = ?');
    $statement->execute([$_SESSION['user_id']]);
    return $statement->fetch() ?: null;
}

function fetchCategories(PDO $pdo, int $userId): array
{
    $statement = $pdo->prepare('SELECT * FROM categories WHERE user_id = ? ORDER BY name ASC');
    $statement->execute([$userId]);
    return $statement->fetchAll();
}

function fetchTags(PDO $pdo, int $userId): array
{
    $statement = $pdo->prepare('SELECT * FROM tags WHERE user_id = ? ORDER BY name ASC');
    $statement->execute([$userId]);
    return $statement->fetchAll();
}

function syncNoteTags(PDO $pdo, int $noteId, int $userId, array $tagNames): void
{
    $pdo->prepare('DELETE FROM note_tags WHERE note_id = ?')->execute([$noteId]);

    $insertTag = $pdo->prepare('INSERT INTO tags (user_id, name) VALUES (?, ?)');
    $findTag = $pdo->prepare('SELECT id FROM tags WHERE user_id = ? AND name = ? LIMIT 1');
    $linkTag = $pdo->prepare('INSERT INTO note_tags (note_id, tag_id) VALUES (?, ?)');

    foreach ($tagNames as $tagName) {
        $tagName = trim($tagName);
        if ($tagName === '') {
            continue;
        }

        $findTag->execute([$userId, $tagName]);
        $tag = $findTag->fetch();

        if (!$tag) {
            $insertTag->execute([$userId, $tagName]);
            $tagId = (int) $pdo->lastInsertId();
        } else {
            $tagId = (int) $tag['id'];
        }

        $linkTag->execute([$noteId, $tagId]);
    }
}

function syncLinkedNotes(PDO $pdo, int $noteId, int $userId, array $linkedNoteIds): void
{
    $pdo->prepare('DELETE FROM linked_notes WHERE note_id = ?')->execute([$noteId]);
    $insertLink = $pdo->prepare('INSERT INTO linked_notes (note_id, linked_note_id) VALUES (?, ?)');
    $verifyNote = $pdo->prepare('SELECT id FROM notes WHERE id = ? AND user_id = ? AND id != ? LIMIT 1');

    foreach ($linkedNoteIds as $linkedNoteId) {
        $linkedNoteId = (int) $linkedNoteId;
        if ($linkedNoteId <= 0) {
            continue;
        }

        $verifyNote->execute([$linkedNoteId, $userId, $noteId]);
        if ($verifyNote->fetch()) {
            $insertLink->execute([$noteId, $linkedNoteId]);
        }
    }
}

function fetchNoteTags(PDO $pdo, int $noteId): array
{
    $statement = $pdo->prepare('SELECT t.* FROM tags t INNER JOIN note_tags nt ON nt.tag_id = t.id WHERE nt.note_id = ? ORDER BY t.name ASC');
    $statement->execute([$noteId]);
    return $statement->fetchAll();
}

function fetchLinkedNotes(PDO $pdo, int $noteId, int $userId): array
{
    $statement = $pdo->prepare('SELECT n.id, n.title, n.priority, n.status FROM linked_notes ln INNER JOIN notes n ON n.id = ln.linked_note_id WHERE ln.note_id = ? AND n.user_id = ? ORDER BY n.updated_at DESC');
    $statement->execute([$noteId, $userId]);
    return $statement->fetchAll();
}

function baseNoteQuery(): string
{
    return 'SELECT n.*, c.name AS category_name,
            GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ", ") AS tags_list
            FROM notes n
            LEFT JOIN categories c ON c.id = n.category_id
            LEFT JOIN note_tags nt ON nt.note_id = n.id
            LEFT JOIN tags t ON t.id = nt.tag_id';
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
