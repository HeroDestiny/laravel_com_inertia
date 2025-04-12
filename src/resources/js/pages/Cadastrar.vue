<template>
  <AppLayout :breadcrumbs="breadcrumbs">

    <Head title="Cadastrar" />

    <Card class="w-full p-6">
      <CardHeader>
        <CardTitle class="text-2xl">Criar Conta</CardTitle>
        <CardDescription>Preencha seus dados abaixo para se registrar.</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="onSubmit" class="space-y-4">
          <div>
            <Label for="name">Nome</Label>
            <Input id="name" v-model="form.name" placeholder="Digite seu nome" required />
          </div>

          <div>
            <Label for="email">Email</Label>
            <Input id="email" v-model="form.email" type="email" placeholder="Digite seu email" required />
          </div>

          <div>
            <Label for="password">Senha</Label>
            <Input id="password" v-model="form.password" type="password" placeholder="Digite sua senha" required />
          </div>

          <Button type="submit" class="w-full">
            Registrar
          </Button>
        </form>
      </CardContent>
    </Card>

  </AppLayout>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head } from '@inertiajs/vue3'


const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

interface RegisterForm {
  name: string
  email: string
  password: string
}

const form = ref<RegisterForm>({
  name: '',
  email: '',
  password: '',
})

const onSubmit = () => {
  console.log('Dados do formulário:', form.value)

  alert("sucesso")

  form.value = {
    name: '',
    email: '',
    password: '',
  }
}
</script>
