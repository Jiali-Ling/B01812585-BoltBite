async function presignAndUpload(file) {
    const resp = await fetch('/boltbite/aws/presign', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({filename: file.name})
    });
    const data = await resp.json();
    const uploadUrl = data.url;
    await fetch(uploadUrl, {method: 'PUT', body: file});
    return data.key || null;
}
