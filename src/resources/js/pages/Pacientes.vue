<script lang="ts" setup>
import CadastroModal from '@/components/CadastroModal.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Paciente {
    id: number;
    name: string;
    surname: string;
    birthdate: string;
    cpf: string;
    role: string | null;
    education: string | null;
    mother_name: string;
    email: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    pacientes: Paciente[];
}

const { pacientes } = defineProps<Props>();

const isModalOpen = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Pacientes',
        href: '/pacientes',
    },
];

const openModal = () => {
    isModalOpen.value = true;
};

const onSuccess = () => {
    // Recarregar a página para mostrar a nova lista
    router.reload();
};

const deletePaciente = (id: number) => {
    if (confirm('Tem certeza que deseja excluir este paciente?')) {
        router.delete(route('pacientes.destroy', id), {
            onSuccess: () => {
                toast.success('Paciente excluído com sucesso!');
            },
            onError: () => {
                toast.error('Erro ao excluir paciente');
            },
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
};

const formatCPF = (cpf: string) => {
    // Remove tudo que não é dígito
    const cleaned = cpf.replace(/\D/g, '');

    // Aplica a máscara
    return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
};
</script>

<template>
    <Head title="Pacientes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Header com botão para abrir modal -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Pacientes</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Gerencie os cadastros de pacientes</p>
                </div>
                <Button @click="openModal" class="flex items-center gap-2">
                    <Plus class="h-4 w-4" />
                    Novo Cadastro
                </Button>
            </div>

            <!-- Card com a lista -->
            <Card>
                <CardHeader>
                    <CardTitle>Lista de Pacientes</CardTitle>
                    <CardDescription>
                        {{ pacientes.length }} {{ pacientes.length === 1 ? 'paciente cadastrado' : 'pacientes cadastrados' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="pacientes.length === 0" class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">Nenhum paciente cadastrado ainda.</p>
                        <Button @click="openModal" variant="outline" class="mt-4">
                            <Plus class="h-4 w-4 mr-2" />
                            Cadastrar primeiro paciente
                        </Button>
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="paciente in pacientes"
                            :key="paciente.id"
                            class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        >
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center gap-4">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ paciente.name }} {{ paciente.surname }}</h3>
                                    <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded-full">
                                        {{ formatCPF(paciente.cpf) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span>{{ paciente.email }}</span>
                                    <span>•</span>
                                    <span>{{ formatDate(paciente.birthdate) }}</span>
                                    <span v-if="paciente.role">•</span>
                                    <span
                                        v-if="paciente.role"
                                        class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full"
                                    >
                                        {{ paciente.role }}
                                    </span>
                                    <span v-if="paciente.education">•</span>
                                    <span
                                        v-if="paciente.education"
                                        class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full"
                                    >
                                        {{ paciente.education }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <Button variant="ghost" size="sm">
                                    <Edit class="h-4 w-4" />
                                </Button>
                                <Button variant="ghost" size="sm" @click="deletePaciente(paciente.id)" class="text-red-600 hover:text-red-700">
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Modal de cadastro -->
            <CadastroModal v-model:open="isModalOpen" @success="onSuccess" />
        </div>
    </AppLayout>
</template>
