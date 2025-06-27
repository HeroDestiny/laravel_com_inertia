<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
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
}

const form = ref<RegisterForm>({
    name: '',
    surname: '',
    birthdate: '',
    cpf: '',
    role: '',
    education: '',
    motherName: '',
    email: '',
});

const onSubmit = () => {
    console.log('Dados do formulário:', form.value);

    toast('Cadastro realizado com sucesso!', {
        description: `Bem-vindo(a), ${form.value.name}!`,
    });

    // Limpar formulário
    form.value = {
        name: '',
        surname: '',
        birthdate: '',
        cpf: '',
        role: '',
        education: '',
        motherName: '',
        email: '',
    };
};
</script>

<template>
    <AppLayout>
        <Head title="Cadastrar" />

        <Card class="w-full p-6">
            <CardHeader>
                <CardTitle class="text-2xl">Criar Conta</CardTitle>
                <CardDescription>Preencha seus dados abaixo para se registrar.</CardDescription>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="onSubmit" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <Label for="name">Nome</Label>
                            <Input id="name" v-model="form.name" placeholder="Digite seu nome" required />
                        </div>

                        <div>
                            <Label for="surname">Sobrenome</Label>
                            <Input id="surname" v-model="form.surname" placeholder="Digite seu sobrenome" required />
                        </div>

                        <div>
                            <Label for="birthdate">Data de Nascimento</Label>
                            <Input id="birthdate" v-model="form.birthdate" type="date" required />
                        </div>

                        <div>
                            <Label for="cpf">CPF</Label>
                            <Input id="cpf" v-model="form.cpf" placeholder="Digite seu CPF" required />
                        </div>

                        <div>
                            <Label for="role">Cargo</Label>
                            <Input id="role" v-model="form.role" placeholder="Digite seu cargo" required />
                        </div>

                        <div>
                            <Label for="education">Escolaridade</Label>
                            <Input id="education" v-model="form.education" placeholder="Digite sua escolaridade" required />
                        </div>

                        <div class="md:col-span-2">
                            <Label for="motherName">Nome da Mãe</Label>
                            <Input id="motherName" v-model="form.motherName" placeholder="Digite o nome da sua mãe" required />
                        </div>

                        <div class="md:col-span-2">
                            <Label for="email">Email</Label>
                            <Input id="email" v-model="form.email" type="email" placeholder="Digite seu email" required />
                        </div>
                    </div>

                    <Button type="submit" class="mt-6 w-full"> Registrar </Button>
                </form>
            </CardContent>
        </Card>
    </AppLayout>
</template>
