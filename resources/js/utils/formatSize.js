export function formatSize(bytes) {
    if (bytes >= 1_048_576) return parseFloat((bytes / 1_048_576).toFixed(1)) + 'MB';
    if (bytes > 0) return Math.ceil(bytes / 1024) + 'KB';
    return 0;
}
