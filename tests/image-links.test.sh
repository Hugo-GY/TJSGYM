#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname "$0")" && pwd)
ROOT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)

ROOT_DIR="$ROOT_DIR" node <<'NODE'
const fs = require('fs');
const path = require('path');
const rootDir = process.env.ROOT_DIR;
const imageExt = /\.(png|jpe?g|webp|svg|ico)$/i;
const sourceFiles = [];
const sourceRoots = [
  path.join(rootDir, 'index.html'),
  path.join(rootDir, 'pages'),
  path.join(rootDir, 'classes'),
  path.join(rootDir, 'news'),
  path.join(rootDir, 'css'),
];

function walk(dir) {
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    if (entry.name.startsWith('.')) continue;
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      walk(fullPath);
      continue;
    }
    if (/\.(html|css)$/i.test(entry.name)) {
      sourceFiles.push(fullPath);
    }
  }
}

function collectRefs(filePath) {
  const source = fs.readFileSync(filePath, 'utf8');
  const refs = [];
  const attrRegex = /\b(?:src|href)=["']([^"']+\.(?:png|jpe?g|webp|svg|ico))["']/gi;
  const cssRegex = /url\((['"]?)([^'")]+\.(?:png|jpe?g|webp|svg|ico))\1\)/gi;

  for (const regex of [attrRegex, cssRegex]) {
    let match;
    while ((match = regex.exec(source))) {
      const ref = match[1] || match[2];
      if (!ref || /^https?:\/\//i.test(ref) || /^data:/i.test(ref)) continue;
      refs.push(ref);
    }
  }
  return refs;
}

async function main() {
  for (const sourceRoot of sourceRoots) {
    if (!fs.existsSync(sourceRoot)) continue;
    const stat = fs.statSync(sourceRoot);
    if (stat.isDirectory()) {
      walk(sourceRoot);
    } else if (stat.isFile()) {
      sourceFiles.push(sourceRoot);
    }
  }

  const missing = [];
  const refsWithSpaces = new Set();

  for (const filePath of sourceFiles) {
    for (const ref of collectRefs(filePath)) {
      if (!imageExt.test(ref)) continue;
      const resolved = path.resolve(path.dirname(filePath), ref);
      if (!fs.existsSync(resolved)) {
        missing.push(`${path.relative(rootDir, filePath)} -> ${ref}`);
      }
      if (ref.includes(' ')) {
        refsWithSpaces.add(ref.startsWith('.') ? ref : `/${ref.replace(/^\.\//, '')}`);
      }
    }
  }

  if (missing.length) {
    console.error('Missing local image refs:');
    for (const line of missing) console.error(`- ${line}`);
    process.exit(1);
  }

  const { normalizeRequestPath } = await import(path.join(rootDir, 'serve.mjs'));

  for (const ref of refsWithSpaces) {
    const encoded = ref.split('/').map(encodeURIComponent).join('/').replace(/%2F/g, '/');
    const normalized = normalizeRequestPath(encoded);
    const resolved = path.join(rootDir, normalized);
    if (!fs.existsSync(resolved)) {
      console.error(`Expected encoded asset path to resolve to a real file: ${encoded} -> ${normalized}`);
      process.exit(1);
    }
  }
}

main().catch((error) => {
  console.error(error.message);
  process.exit(1);
});
NODE
