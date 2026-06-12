const http = require('http');

const PORT = 8000;
const TARGET_PORT = 8001;
const TARGET_HOST = '127.0.0.1';

const server = http.createServer((req, res) => {
    // Forward the request to PHP built-in server
    const options = {
        hostname: TARGET_HOST,
        port: TARGET_PORT,
        path: req.url,
        method: req.method,
        headers: {
            ...req.headers,
            host: `${TARGET_HOST}:${TARGET_PORT}`,
            connection: 'close'
        }
    };

    const proxyReq = http.request(options, (proxyRes) => {
        res.writeHead(proxyRes.statusCode, proxyRes.headers);
        proxyRes.pipe(res, { end: true });
    });

    proxyReq.on('error', (err) => {
        console.error('Proxy Error:', err.message);
        res.writeHead(502, { 'Content-Type': 'text/plain' });
        res.end('Bad Gateway: PHP server on port 8001 might be down.');
    });

    req.pipe(proxyReq, { end: true });
});

server.listen(PORT, '0.0.0.0', () => {
    console.log(`Proxy server listening on 0.0.0.0:${PORT} -> Forwarding to 127.0.0.1:${TARGET_PORT}`);
});
