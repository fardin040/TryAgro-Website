<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Extract YouTube video id and return an embeddable URL.
 * Keeping this logic from the main branch to handle dynamic content.
 */
function youtube_embed_url(string $link): ?string
{
    $trimmed = trim($link);
    if ($trimmed === '') return null;

    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $trimmed) === 1) {
        return 'https://www.youtube.com/embed/' . $trimmed;
    }

    $parts = parse_url($trimmed);
    if (!is_array($parts)) return null;

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

$videos = db_fetch_all('SELECT id, title, youtube_link FROM videos ORDER BY id DESC');

$pageTitle = 'Videos | ' . SITE_NAME;
require_once PROJECT_ROOT . '/header.php';
?>

<section class="page-intro container section">
    <p class="eyebrow">Learning hub</p>
    <h1>Watch practical tips from the field</h1>
    <p>Explore short guides on nutrient timing, soil care, and crop protection best practices.</p>
</section>

<section class="container section">
    <div class="card-grid">
        <?php foreach ($videos as $video) : ?>
            <?php $embedUrl = youtube_embed_url((string) $video['youtube_link']); ?>
            
            <article class="card">
                <h2><?php echo sanitize_output($video['title']); ?></h2>
                
                <?php if ($embedUrl !== null) : ?>
                    <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; margin-top: 1rem;">
                        <iframe 
                            src="<?php echo sanitize_output($embedUrl); ?>" 
                            title="<?php echo sanitize_output($video['title']); ?>" 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                            allowfullscreen>
                        </iframe>
                    </div>
                <?php else : ?>
                    <p class="alert-error" style="margin-top: 1rem;">Invalid YouTube link stored for this video.</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>

        <?php if ($videos === []) : ?>
            <p class="muted">No video guides are available yet. Please check back soon.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once PROJECT_ROOT . '/footer.php'; ?>