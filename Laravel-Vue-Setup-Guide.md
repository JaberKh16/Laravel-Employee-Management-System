# Vue.js Setup in Laravel 8 from Scratch (Different Ways)

Laravel 8 ships with **Laravel Mix** (webpack wrapper) by default, but you can also use **Vite** or **CDN**. Here are all the main approaches.

---

## 🧰 Prerequisites

```bash
composer create-project laravel/laravel laravel-vue "8.*"
cd laravel-vue
```

---

## Method 1: Vue 2 with Laravel Mix (Default / Easiest)

Laravel 8 ships with `laravel/ui` scaffolding for Vue 2.

### Step 1: Install laravel/ui
```bash
composer require laravel/ui
```

### Step 2: Scaffold Vue
```bash
php artisan ui vue
# or with auth pages
php artisan ui vue --auth
```

This creates:
- `resources/js/app.js` with Vue bootstrap
- `resources/js/components/ExampleComponent.vue`
- Updates `package.json`, `webpack.mix.js`

### Step 3: Install NPM packages
```bash
npm install
```

### Step 4: Compile
```bash
npm run dev      # development
npm run watch    # auto-rebuild
npm run prod     # production minified
```

### Step 5: Add mount point in Blade
`resources/views/welcome.blade.php`:
```blade
<div id="app">
    <example-component></example-component>
</div>
<script src="{{ mix('js/app.js') }}"></script>
```

### `resources/js/app.js` (generated)
```js
require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
});
```

### `webpack.mix.js`
```js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css');
```

---

## Method 2: Vue 3 with Laravel Mix

Laravel 8's default scaffolding targets Vue 2. For **Vue 3**, install manually.

### Step 1: Install dependencies
```bash
npm install vue@next @vue/compiler-sfc vue-loader@next --save-dev
```

### Step 2: Configure `webpack.mix.js`
```js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 3 })
   .postCss('resources/css/app.css', 'public/css', [
       require('postcss-import'),
   ]);
```

### Step 3: Update `resources/js/app.js`
```js
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';

const app = createApp({});
app.component('example-component', ExampleComponent);
app.mount('#app');
```

### Step 4: Example component (`ExampleComponent.vue`)
```vue
<template>
  <div class="container">
    <h1>{{ message }}</h1>
  </div>
</template>

<script>
export default {
  data() {
    return { message: 'Hello Vue 3 in Laravel 8!' };
  }
}
</script>
```

### Step 5: Compile
```bash
npm install
npm run dev
```

---

## Method 3: Vue 3 with Vite (Modern, Fastest ⚡)

If you want HMR speed like Laravel 9+.

### Step 1: Install Vite + Vue plugin
```bash
npm install vite @vitejs/plugin-vue --save-dev
npm install vue@next
```

### Step 2: Create `vite.config.js` in project root
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
```

> Install `laravel-vite-plugin`:
> ```bash
> npm install laravel-vite-plugin --save-dev
> ```

### Step 3: Update `package.json` scripts
```json
"scripts": {
    "dev": "vite",
    "build": "vite build"
}
```

### Step 4: `resources/js/app.js`
```js
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';

createApp({
    components: { ExampleComponent }
}).mount('#app');
```

### Step 5: Use in Blade (Laravel 8 doesn't have `@vite` — add manually)

Add to `AppServiceProvider::boot()` or create a helper:
```php
use Illuminate\Support\Facades\Vite; // only Laravel 9+
```

For Laravel 8, add this to your Blade:
```blade
@if(app()->environment('local'))
    <script type="module" src="http://localhost:3000/@vite/client"></script>
    <script type="module" src="http://localhost:3000/resources/js/app.js"></script>
@else
    <script type="module" src="{{ asset('build/assets/app.js') }}"></script>
@endif
```

Or create a custom Blade directive in `AppServiceProvider`:
```php
Blade::directive('vite', function () {
    if (app()->environment('local')) {
        return "<script type=\"module\" src=\"http://localhost:3000/@vite/client\"></script>
                <script type=\"module\" src=\"http://localhost:3000/resources/js/app.js\"></script>";
    }
    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    $file = $manifest['resources/js/app.js']['file'];
    return "<script type=\"module\" src=\"/build/{$file}\"></script>";
});
```

### Step 6: Run
```bash
npm run dev
```

---

## Method 4: CDN (No Build Step, Quick Prototype)

Perfect for tiny projects or testing.

### In Blade file:
```blade
<!DOCTYPE html>
<html>
<head>
    <title>Vue CDN</title>
</head>
<body>
    <div id="app">
        <h1>@{{ message }}</h1>
        <button @click="count++">Clicked @{{ count }} times</button>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        const { createApp } = Vue;
        createApp({
            data() {
                return { message: 'Hello from CDN', count: 0 }
            }
        }).mount('#app');
    </script>
</body>
</html>
```

⚠️ Use `@{{ }}` to escape Blade's curly braces.

---

## Method 5: Inertia.js + Vue (SPA-like without API)

Great if you want a full SPA feel while keeping Laravel routing.

### Step 1: Install
```bash
composer require inertiajs/inertia-laravel
npm install @inertiajs/inertia @inertiajs/inertia-vue3 vue@next
```

### Step 2: Middleware
```bash
php artisan inertia:middleware
```
Register in `app/Http/Kernel.php`:
```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\HandleInertiaRequests::class,
    ],
];
```

### Step 3: Root Blade `resources/views/app.blade.php`
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @inertiaHead
</head>
<body>
    @inertia
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
```

### Step 4: `resources/js/app.js`
```js
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';

createInertiaApp({
    resolve: name => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .mount(el);
    },
});
```

### Step 5: Route in Laravel
```php
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Home', ['name' => 'Laravel']));
```

### Step 6: Page `resources/js/Pages/Home.vue`
```vue
<template>
  <h1>Hello {{ name }}</h1>
</template>
<script>
export default { props: ['name'] }
</script>
```

---

## 📊 Comparison Table

| Method | Vue | Build Tool | SPA Ready | Best For |
|--------|-----|-----------|-----------|----------|
| laravel/ui | 2 | Mix | Partial | Legacy, quick |
| Manual Vue 3 + Mix | 3 | Mix | Partial | Vue 3 with Laravel 8 |
| Vite + Vue 3 | 3 | Vite | Partial | Modern, fast HMR |
| CDN | 2/3 | None | No | Prototypes |
| Inertia + Vue 3 | 3 | Mix/Vite | Yes | Full SPA feel |

---

## 🔧 Common Commands Cheat Sheet

```bash
# Mix
npm run dev
npm run watch
npm run prod

# Vite
npm run dev
npm run build

# Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## ⚠️ Common Pitfalls in Laravel 8

1. **Vue 3 with Mix needs `vue-loader@next`** — otherwise you get compiler errors.
2. **`@vite` directive doesn't exist in Laravel 8** — you must add Blade output manually.
3. **`mix()` helper needs manifest** — run `npm run dev` at least once before rendering views.
4. **CSP / HMR port 3000** — ensure port is open in dev; use `--host` flag if needed.
5. **`@{{ }}` in CDN mode** — always escape Vue moustaches from Blade.

---

If you tell me which approach you want (Vue 2, Vue 3, Vite, or Inertia), I can give you a complete minimal starter project with all files ready to copy-paste.
