<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Extract YouTube video id from common URL formats.
 */
function youtube_embed_url(string $link): ?string
{
    $trimmed = trim($link);
    if ($trimmed === '') {
        return null;
    }

    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $trimmed) === 1) {
        return 'https://www.youtube.com/embed/' . $trimmed;
    }

    $parts = parse_url($trimmed);
    if (!is_array($parts)) {
        return null;
    }

    $host = strtolower((string) ($parts['host'] ?? ''));
    $path = (string) ($parts['path'] ?? '');
    parse_str((string) ($parts['query'] ?? ''), $query);

    $videoId = null;

    if (($host === 'youtu.be' || $host === 'www.youtu.be') && $path !== '') {
        $videoId = trim($path, '/');
    } elseif (str_contains($host, 'youtube.com')) {
        if (($query['v'] ?? '') !== '') {
            $videoId = (string) $query['v'];
        } elseif (preg_match('#^/embed/([a-zA-Z0-9_-]{11})$#', $path, $matches) === 1) {
            $videoId = $matches[1];
        }
    }

    if ($videoId === null || preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId) !== 1) {
        return null;
    }

    return 'https://www.youtube.com/embed/' . $videoId;
}

$pageTitle = 'Videos | ' . SITE_NAME;
$videos = db_fetch_all('SELECT id, title, youtube_link FROM videos ORDER BY id DESC');

require_once PROJECT_ROOT . '/header.php';
?>
<section>
    <h2>Videos</h2>
    <div class="grid grid-2">
        <?php foreach ($videos as $video) : ?>
            <?php $embedUrl = youtube_embed_url((string) $video['youtube_link']); ?>
            <article class="card">
                <h3><?php echo sanitize_output($video['title']); ?></h3>
                <?php if ($embedUrl !== null) : ?>
                    <iframe src="<?php echo sanitize_output($embedUrl); ?>" title="<?php echo sanitize_output($video['title']); ?>" allowfullscreen></iframe>
                <?php else : ?>
                    <p class="alert-error">Invalid YouTube link stored for this video.</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if ($videos === []) : ?>
            <p class="muted">No videos available.</p>
        <?php endif; ?>
    </div>
</section>
<?php require_once PROJECT_ROOT . '/footer.php'; ?>
