# Arquitetura do Frontend (Vue.js + Inertia.js)

Documentação detalhada da arquitetura frontend do projeto.

## Visão Geral

O frontend utiliza Vue.js 3 com Composition API, TypeScript para tipagem estática, e Inertia.js como bridge para o backend Laravel.

## Stack Tecnológica

-   **Vue.js 3**: Framework JavaScript progressivo
-   **TypeScript**: Superset tipado do JavaScript
-   **Inertia.js**: Adaptador para SPAs modernas
-   **Tailwind CSS**: Framework CSS utilitário
-   **Vite**: Build tool e dev server
-   **ESLint**: Linter para JavaScript/TypeScript

## Estrutura do Frontend

```
src/resources/
├── js/                        # Código JavaScript/TypeScript
│   ├── Components/            # Componentes reutilizáveis
│   ├── Layouts/              # Layouts base
│   ├── Pages/                # Páginas da aplicação
│   ├── Types/                # Definições TypeScript
│   ├── Utils/                # Funções utilitárias
│   ├── app.ts                # Entry point
│   └── bootstrap.ts          # Configuração inicial
├── css/                      # Estilos CSS
│   └── app.css              # Estilos principais
└── views/                    # Templates blade
    └── app.blade.php         # Template principal
```

## Configuração Base

### Entry Point (app.ts)

```typescript
import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
```

### Bootstrap Configuration

```typescript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF Token configuration
let token = document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
```

## Definições TypeScript

### Global Types

```typescript
// types/global.d.ts
import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    var route: typeof ziggyRoute;
    var Ziggy: any;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Paciente {
    id: number;
    name: string;
    surname: string;
    birthdate: string;
    cpf: string;
    role?: string;
    education?: string;
    mother_name: string;
    email: string;
    created_at: string;
    updated_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    flash: {
        success?: string;
        error?: string;
    };
};
```

### Component Props Types

```typescript
// types/components.d.ts
export interface ButtonProps {
    type?: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    variant?: 'primary' | 'secondary' | 'danger';
    size?: 'sm' | 'md' | 'lg';
}

export interface InputProps {
    id: string;
    type?: 'text' | 'email' | 'password' | 'number' | 'date';
    modelValue: string | number;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
    error?: string;
}

export interface ModalProps {
    show?: boolean;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
    closeable?: boolean;
}
```

## Layouts

### Authenticated Layout

```vue
<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')"> Dashboard </NavLink>
                                <NavLink :href="route('pacientes.index')" :active="route().current('pacientes.*')"> Pacientes </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button"> Log Out </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"> Dashboard </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('pacientes.index')" :active="route().current('pacientes.*')"> Pacientes </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button"> Log Out </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>
```

### Guest Layout

```vue
<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>
```

## Componentes Reutilizáveis

### Input Component

```vue
<template>
    <input
        :id="id"
        ref="input"
        :value="modelValue"
        :type="type"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :class="{ 'border-red-500': error }"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

interface Props {
    id: string;
    type?: 'text' | 'email' | 'password' | 'number' | 'date';
    modelValue: string | number;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
    error?: string;
    autofocus?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    required: false,
    disabled: false,
    autofocus: false,
});

defineEmits<{
    'update:modelValue': [value: string];
}>();

const input = ref<HTMLInputElement>();

onMounted(() => {
    if (props.autofocus) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>
```

### Button Component

```vue
<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
        :class="[disabled ? 'opacity-25 cursor-not-allowed' : '', variantClasses[variant], sizeClasses[size]]"
    >
        <slot />
    </button>
</template>

<script setup lang="ts">
interface Props {
    type?: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    variant?: 'primary' | 'secondary' | 'danger';
    size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
    type: 'button',
    disabled: false,
    variant: 'primary',
    size: 'md',
});

const variantClasses = {
    primary: 'bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500',
    secondary: 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-indigo-500',
    danger: 'bg-red-600 text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500',
};

const sizeClasses = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-4 py-2 text-xs',
    lg: 'px-6 py-3 text-sm',
};
</script>
```

### Modal Component

```vue
<template>
    <teleport to="body">
        <Transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </Transition>

                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <slot v-if="show" />
                    </div>
                </Transition>
            </div>
        </Transition>
    </teleport>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue';

interface Props {
    show?: boolean;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
    closeable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    maxWidth: '2xl',
    closeable: true,
});

const emit = defineEmits<{
    close: [];
}>();

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = 'visible';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>
```

## Páginas

### Dashboard Page

```vue
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Statistics Cards -->
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-blue-800">Total de Pacientes</h3>
                                <p class="text-3xl font-bold text-blue-600">{{ statistics.total }}</p>
                            </div>

                            <div class="bg-green-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-green-800">Pacientes Ativos</h3>
                                <p class="text-3xl font-bold text-green-600">{{ statistics.active }}</p>
                            </div>

                            <div class="bg-yellow-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-yellow-800">Novos esta Semana</h3>
                                <p class="text-3xl font-bold text-yellow-600">{{ statistics.recent }}</p>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Ações Rápidas</h3>
                            <div class="flex space-x-4">
                                <PrimaryButton @click="router.visit(route('pacientes.create'))"> Novo Paciente </PrimaryButton>
                                <SecondaryButton @click="router.visit(route('pacientes.index'))"> Ver Todos </SecondaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

interface Props {
    statistics: {
        total: number;
        active: number;
        recent: number;
    };
}

defineProps<Props>();
</script>
```

### Pacientes Index Page

```vue
<template>
    <Head title="Pacientes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pacientes</h2>
                <PrimaryButton @click="router.visit(route('pacientes.create'))"> Novo Paciente </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Search -->
                        <div class="mb-6">
                            <TextInput id="search" v-model="search" type="text" placeholder="Buscar pacientes..." @input="handleSearch" />
                        </div>

                        <!-- Patients Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data de Nascimento
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="paciente in pacientes.data" :key="paciente.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ paciente.name }} {{ paciente.surname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ paciente.email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ paciente.cpf }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ formatDate(paciente.birthdate) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <SecondaryButton @click="router.visit(route('pacientes.show', paciente.id))"> Ver </SecondaryButton>
                                                <SecondaryButton @click="router.visit(route('pacientes.edit', paciente.id))">
                                                    Editar
                                                </SecondaryButton>
                                                <DangerButton @click="confirmDelete(paciente)"> Excluir </DangerButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <Pagination :links="pacientes.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showingDeleteModal" @close="showingDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Confirmar Exclusão</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tem certeza que deseja excluir o paciente {{ patientToDelete?.name }}? Esta ação não pode ser desfeita.
                </p>

                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="showingDeleteModal = false"> Cancelar </SecondaryButton>

                    <DangerButton @click="deletePaciente"> Excluir </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import { Paciente } from '@/types/global';

interface Props {
    pacientes: {
        data: Paciente[];
        links: any[];
    };
}

defineProps<Props>();

const search = ref('');
const showingDeleteModal = ref(false);
const patientToDelete = ref<Paciente | null>(null);

const handleSearch = () => {
    router.get(
        route('pacientes.index'),
        { search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const confirmDelete = (paciente: Paciente) => {
    patientToDelete.value = paciente;
    showingDeleteModal.value = true;
};

const deletePaciente = () => {
    if (patientToDelete.value) {
        router.delete(route('pacientes.destroy', patientToDelete.value.id), {
            onSuccess: () => {
                showingDeleteModal.value = false;
                patientToDelete.value = null;
            },
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
};
</script>
```

## Estado e Gerenciamento de Dados

### Inertia.js State Management

```typescript
// Shared data across pages
import { usePage } from '@inertiajs/vue3';

const page = usePage();

// Access shared props
const user = computed(() => page.props.auth.user);
const flash = computed(() => page.props.flash);

// Navigate with state preservation
router.visit('/pacientes', {
    preserveState: true,
    preserveScroll: true,
});
```

### Form Handling

```typescript
// Form composition
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    surname: '',
    email: '',
    cpf: '',
    birthdate: '',
    mother_name: '',
});

const submit = () => {
    form.post(route('pacientes.store'), {
        onSuccess: () => form.reset(),
        onError: () => {
            // Handle validation errors
            console.log(form.errors);
        },
    });
};
```

## Build e Configuração

### Vite Configuration

```typescript
// vite.config.ts
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

### TypeScript Configuration

```json
{
    "compilerOptions": {
        "target": "ES2020",
        "useDefineForClassFields": true,
        "lib": ["ES2020", "DOM", "DOM.Iterable"],
        "module": "ESNext",
        "skipLibCheck": true,
        "moduleResolution": "bundler",
        "allowImportingTsExtensions": true,
        "resolveJsonModule": true,
        "isolatedModules": true,
        "noEmit": true,
        "jsx": "preserve",
        "strict": true,
        "noUnusedLocals": true,
        "noUnusedParameters": true,
        "noFallthroughCasesInSwitch": true,
        "baseUrl": ".",
        "paths": {
            "@/*": ["resources/js/*"]
        }
    },
    "include": ["resources/js/**/*.ts", "resources/js/**/*.d.ts", "resources/js/**/*.vue"],
    "references": [{ "path": "./tsconfig.node.json" }]
}
```

### ESLint Configuration

```javascript
// eslint.config.js
import js from '@eslint/js';
import vue from 'eslint-plugin-vue';
import typescript from '@typescript-eslint/eslint-plugin';

export default [
    js.configs.recommended,
    ...vue.configs['flat/recommended'],
    {
        files: ['**/*.{js,mjs,cjs,ts,vue}'],
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: 'module',
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/require-default-prop': 'off',
            '@typescript-eslint/no-unused-vars': 'error',
        },
    },
];
```

## Performance e Otimização

### Code Splitting

```typescript
// Lazy loading of pages
const pages = import.meta.glob('./Pages/**/*.vue');

const resolvePageComponent = (name: string) => {
    const page = pages[`./Pages/${name}.vue`];
    if (!page) {
        throw new Error(`Page not found: ${name}`);
    }
    return page().then((module) => module.default);
};
```

### Asset Optimization

```css
/* Critical CSS */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* Component-specific styles */
@layer components {
    .btn-primary {
        @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
    }
}
```

### Bundle Analysis

```bash
# Analyze bundle size
npm run build -- --analyze

# Check for unused code
npm run build -- --debug
```

## Testing Frontend

### Component Testing

```typescript
// tests/js/components/Button.test.ts
import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import PrimaryButton from '@/Components/PrimaryButton.vue';

describe('PrimaryButton', () => {
    it('renders button text', () => {
        const wrapper = mount(PrimaryButton, {
            slots: {
                default: 'Click me',
            },
        });

        expect(wrapper.text()).toBe('Click me');
    });

    it('applies primary variant classes', () => {
        const wrapper = mount(PrimaryButton, {
            props: { variant: 'primary' },
        });

        expect(wrapper.classes()).toContain('bg-gray-800');
    });

    it('emits click event', async () => {
        const wrapper = mount(PrimaryButton);

        await wrapper.trigger('click');

        expect(wrapper.emitted()).toHaveProperty('click');
    });
});
```

### E2E Testing

```typescript
// tests/e2e/pacientes.spec.ts
import { test, expect } from '@playwright/test';

test('user can create a patient', async ({ page }) => {
    await page.goto('/login');

    // Login
    await page.fill('[name="email"]', 'test@example.com');
    await page.fill('[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // Navigate to patients
    await page.click('text=Pacientes');
    await page.click('text=Novo Paciente');

    // Fill form
    await page.fill('[name="name"]', 'João');
    await page.fill('[name="surname"]', 'Silva');
    await page.fill('[name="email"]', 'joao@example.com');
    await page.fill('[name="cpf"]', '12345678901');
    await page.fill('[name="birthdate"]', '1990-01-01');
    await page.fill('[name="mother_name"]', 'Maria Silva');

    // Submit
    await page.click('button[type="submit"]');

    // Verify
    await expect(page).toHaveURL('/pacientes');
    await expect(page.locator('text=João Silva')).toBeVisible();
});
```

---

**Próximos passos**: [Database Schema](./DATABASE.md) | [Testing Strategy](../testing/README.md) | [Deployment Guide](../deployment/README.md)
