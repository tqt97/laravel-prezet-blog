# 🚀 Laravel Markdown Blog

A **lightweight yet powerful blog system** built with **Laravel** and **Markdown**.
Easily manage your content using Markdown files with **YAML front matter metadata** for titles, categories, tags, authors, and more.

---

## 👨‍💻 Author

- **Name:** **TuanTQ**
- **Email:** <quoctuanit2018@gmail.com>
- **GitHub:** [tqt97](https://github.com/tqt97)

---

## ✨ Features

- 📝 Write posts in **Markdown** with **YAML front matter**.
- 📂 Organize content by **category**, **tags**, and **author**.
- 🔑 Automatic **slug** & **UUID key** generation.
- 🌓 Support for **draft** & **published** modes.
- 🎨 Customizable **post templates** for faster writing.
- ⚡ Simple CLI command to **generate new posts**.
- 🔍 SEO-friendly URL structure.
- 🌍 Easy deployment on any **Laravel-supported environment**.

---

## 🛠 Tech Stack

- **Backend:** [Laravel 12+](https://laravel.com/)
- **Content:** Markdown (`.md`) with YAML front matter
- **Storage:** Local file system (`prezet/content/`)
- **CLI Tool:** Artisan commands for content generation
- **SEO:** Auto-generated slugs & meta information

---

## 📑 Front Matter Structure

Every `.md` file starts with a **YAML block** for metadata:

```yaml
---
title: Hello World
excerpt: This is a short description of the post.
category: Laravel
image: /prezet/img/laravel.jpg
draft: false
date: 2025-08-24
author: bob
slug: hello-world
key: 92d0f1d3-a9e1-4952-9060-ade1ea6f0061
tags: ['laravel', 'js']
---

Content go here!
```

---

## Usage

### 1️⃣. Create a new content file

```bash
php artisan make:content "Hello World"
```

This will create:

```md
prezet/content/hello-world.md
```

---

### 2️⃣. Options

- **Custom path**

```bash
php artisan make:content "Hello World" --path=prezet/content/blog
```

- **Category**

```bash
php artisan make:content "Hello World" --category=Laravel
```

- **Author**

```bash
php artisan make:content "Hello World" --author=jane
```

- **Tags** (pass as an array string)

```bash
php artisan make:content "Hello World" --tags="['laravel','php','vue']"
```

- **Draft mode**
By default, new files are created with `draft: true`.
To create a published file:

```bash
php artisan make:content "Hello World" --draft=false
```

---

### 3️⃣. Full Example

```bash
php artisan make:content "A PHP framework with a robust ecosystem"   --path=prezet/content/frameworks   --category=Laravel   --author=bob   --tags="['laravel','php']"   --draft=false
```

This generates a file:

```yaml
---
title: A PHP framework with a robust ecosystem
excerpt:
category: Laravel
image: null
draft: false
date: 2025-08-24
author: bob
slug: a-php-framework-with-a-robust-ecosystem
key: 4f3c12ab-1234-4bcd-9f11-88a99e22c0d5
tags: ['laravel','php']
---

Content go here!
```

---

## 🔄 SQLite Index

Project uses an SQLite index file to more efficiently query information about your markdown content. This index is crucial for features like pagination, sorting, and filtering of your blog posts or documentation pages.

### Managing the Index

```bash
php artisan prezet:index
```

You should run this command whenever you:

- Add a new markdown file
- Change a markdown file's slug
- Modify frontmatter and want to see those changes reflected on index pages

>Note that changes to the main content of your markdown files don't require updating the index, as this content is read directly from the file when displaying a single post.

```bash
php artisan prezet:index --fresh
```

`--fresh` option that will create a new sqlite database and run the prezet migrations before inserting your markdown data. You should run this command whenever you:

- Update to a new version of Prezet
- Are creating an index in a CI/CD pipeline
- Deploy your application to an environment where the index sqlite file is not already present

### Automatically Updating the Index

```bash
npm run dev
```

### Sitemap Generation

The sitemap is automatically generated whenever you run the index update command:

```bash
php artisan prezet:index
```

This command not only updates the content index but also creates or updates the **prezet_sitemap.xml** file in your Laravel project's public directory. This integration ensures that your sitemap always reflects the current state of your content.

---

## 📌 Notes

- 📂 Default storage path is `prezet/content/`.
- 🆔 `key` is generated as a UUID v4.
- 📝 `slug` is automatically generated from the title.
- 📅 `date` defaults to the current day (`Y-m-d`).

---

## 📜 License

This project is open-sourced under the MIT License.
