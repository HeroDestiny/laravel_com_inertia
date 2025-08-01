<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

interface RegisterForm {
    name: string;
    surname: string;
    birthdate: string;
    cpf: string;
    role: string;
    education: string;
    motherName: string;
    email: string;
    [key: string]: any;
}

interface Props {
    open: boolean;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    'update:open': [value: boolean];
    success: [];
}>();

const form = useForm<RegisterForm>({
    name: '',
    surname: '',
    birthdate: '',
    cpf: '',
    role: '',
    education: '',
    motherName: '',
    email: '',
});

const isOpen = ref(props.open);

// Sincronizar o estado interno com as props
watch(
    () => props.open,
    (newValue) => {
        isOpen.value = newValue;
    },
);

// Emitir mudanças para o componente pai
watch(isOpen, (newValue) => {
    emit('update:open', newValue);
});

// Função para aplicar máscara de CPF
const formatCPF = (value: string) => {
    // Remove tudo que não é dígito
    const cleaned = value.replace(/\D/g, '');

    // Limita a 11 dígitos
    const limitedCleaned = cleaned.substring(0, 11);

    // Aplica a máscara progressivamente
    if (limitedCleaned.length <= 3) {
        return limitedCleaned;
    } else if (limitedCleaned.length <= 6) {
        return `${limitedCleaned.substring(0, 3)}.${limitedCleaned.substring(3)}`;
    } else if (limitedCleaned.length <= 9) {
        return `${limitedCleaned.substring(0, 3)}.${limitedCleaned.substring(3, 6)}.${limitedCleaned.substring(6)}`;
    } else {
        return `${limitedCleaned.substring(0, 3)}.${limitedCleaned.substring(3, 6)}.${limitedCleaned.substring(6, 9)}-${limitedCleaned.substring(9)}`;
    }
};

// Função para lidar com input do CPF
const handleCPFInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const cursorPosition = target.selectionStart || 0;
    const oldValue = target.value;
    const formatted = formatCPF(target.value);

    // Atualiza o valor do formulário
    form.cpf = formatted;

    // Calcula a nova posição do cursor
    let newCursorPosition = cursorPosition;
    if (formatted.length > oldValue.length) {
        // Se um caractere foi adicionado (ponto ou hífen), move o cursor para frente
        newCursorPosition = cursorPosition + (formatted.length - oldValue.length);
    }

    // Atualiza o valor do input e reposiciona o cursor
    setTimeout(() => {
        target.value = formatted;
        target.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
};

const onSubmit = () => {
    // Remove formatação do CPF antes de enviar
    const cleanCPF = form.cpf.replace(/\D/g, '');

    form.transform((data) => ({
        ...data,
        cpf: cleanCPF,
    })).post(route('pacientes.store'), {
        onSuccess: () => {
            toast.success('Cadastro realizado com sucesso!', {
                description: `Bem-vindo(a), ${form.name}!`,
            });

            // Limpar formulário
            form.reset();

            // Fechar modal
            isOpen.value = false;

            // Emitir evento de sucesso para atualizar a lista
            emit('success');
        },
        onError: (errors) => {
            console.error('Erros de validação:', errors);
            toast.error('Erro no cadastro', {
                description: 'Por favor, verifique os dados e tente novamente.',
            });
        },
    });
};

const closeModal = () => {
    // Limpar erros e resetar formulário ao fechar
    form.clearErrors();
    form.reset();
    isOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="max-h-[90vh] max-w-2xl overflow-y-auto">
            <DialogHeader>
                <DialogTitle class="text-2xl">Novo Cadastro</DialogTitle>
                <DialogDescription> Preencha os dados abaixo para criar um novo cadastro. </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="onSubmit" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <Label for="modal-name">Nome</Label>
                        <Input
                            id="modal-name"
                            v-model="form.name"
                            placeholder="Digite o nome"
                            required
                            :class="{ 'border-red-500': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <Label for="modal-surname">Sobrenome</Label>
                        <Input
                            id="modal-surname"
                            v-model="form.surname"
                            placeholder="Digite o sobrenome"
                            required
                            :class="{ 'border-red-500': form.errors.surname }"
                        />
                        <p v-if="form.errors.surname" class="mt-1 text-sm text-red-500">{{ form.errors.surname }}</p>
                    </div>

                    <div>
                        <Label for="modal-birthdate">Data de Nascimento</Label>
                        <Input
                            id="modal-birthdate"
                            v-model="form.birthdate"
                            type="date"
                            required
                            :class="{ 'border-red-500': form.errors.birthdate }"
                        />
                        <p v-if="form.errors.birthdate" class="mt-1 text-sm text-red-500">{{ form.errors.birthdate }}</p>
                    </div>

                    <div>
                        <Label for="modal-cpf">CPF</Label>
                        <Input
                            id="modal-cpf"
                            v-model="form.cpf"
                            @input="handleCPFInput"
                            placeholder="000.000.000-00"
                            maxlength="14"
                            required
                            :class="{ 'border-red-500': form.errors.cpf }"
                        />
                        <p v-if="form.errors.cpf" class="mt-1 text-sm text-red-500">{{ form.errors.cpf }}</p>
                    </div>

                    <div>
                        <Label for="modal-role">Cargo</Label>
                        <Input
                            id="modal-role"
                            v-model="form.role"
                            placeholder="Digite o cargo"
                            required
                            :class="{ 'border-red-500': form.errors.role }"
                        />
                        <p v-if="form.errors.role" class="mt-1 text-sm text-red-500">{{ form.errors.role }}</p>
                    </div>

                    <div>
                        <Label for="modal-education">Escolaridade</Label>
                        <select
                            id="modal-education"
                            v-model="form.education"
                            required
                            :class="[
                                'border-input placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:outline-none focus-visible:ring-1 disabled:cursor-not-allowed disabled:opacity-50',
                                { 'border-red-500': form.errors.education },
                            ]"
                        >
                            <option value="">Selecione a escolaridade</option>
                            <option value="Fundamental Incompleto">Fundamental Incompleto</option>
                            <option value="Fundamental Completo">Fundamental Completo</option>
                            <option value="Médio Incompleto">Médio Incompleto</option>
                            <option value="Médio Completo">Médio Completo</option>
                            <option value="Superior Incompleto">Superior Incompleto</option>
                            <option value="Superior Completo">Superior Completo</option>
                            <option value="Pós-graduação">Pós-graduação</option>
                            <option value="Mestrado">Mestrado</option>
                            <option value="Doutorado">Doutorado</option>
                        </select>
                        <p v-if="form.errors.education" class="mt-1 text-sm text-red-500">{{ form.errors.education }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <Label for="modal-motherName">Nome da Mãe</Label>
                        <Input
                            id="modal-motherName"
                            v-model="form.motherName"
                            placeholder="Digite o nome da mãe"
                            required
                            :class="{ 'border-red-500': form.errors.motherName }"
                        />
                        <p v-if="form.errors.motherName" class="mt-1 text-sm text-red-500">{{ form.errors.motherName }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <Label for="modal-email">Email</Label>
                        <Input
                            id="modal-email"
                            v-model="form.email"
                            type="email"
                            placeholder="Digite o email"
                            required
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">{{ form.errors.email }}</p>
                    </div>
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal" type="button"> Cancelar </Button>
                    </DialogClose>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Cadastrando...' : 'Cadastrar' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
