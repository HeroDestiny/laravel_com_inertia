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

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label for="name">Nome</Label>
              <Input id="name" v-model="form.name" placeholder="Digite seu nome"  />
            </div>

            <div>
              <Label for="surname">Sobrenome</Label>
              <Input id="surname" v-model="form.surname" placeholder="Digite seu sobrenome"  />
            </div>

            <div>
              <Label for="birthdate">Data de Nascimento</Label>
              <Input id="birthdate" v-model="form.birthdate" type="date"  />
            </div>

            <div>
              <Label for="cpf">CPF</Label>
              <Input id="cpf" v-model="form.cpf" placeholder="Digite seu CPF"  />
            </div>

            <div>
              <Label for="role">Cargo</Label>
              <Input id="role" v-model="form.role" placeholder="Digite seu cargo"  />
            </div>

            <div>
              <Label for="education">Escolaridade</Label>
              <Input id="education" v-model="form.education" placeholder="Digite sua escolaridade"  />
            </div>

            <div class="md:col-span-2">
              <Label for="motherName">Nome da Mãe</Label>
              <Input id="motherName" v-model="form.motherName" placeholder="Digite o nome da sua mãe"  />
            </div>

            <div class="md:col-span-2">
              <Label for="email">Email</Label>
              <Input id="email" v-model="form.email" type="email" placeholder="Digite seu email"  />
            </div>

          </div>

          <Button type="submit" class="w-full mt-6">
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
import { Head } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'

interface RegisterForm {
  name: string
  surname: string
  birthdate: string
  cpf: string
  role: string
  education: string
  motherName: string
  email: string
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
})

const onSubmit = () => {
  console.log('Dados do formulário:', form.value)

  toast('Cadastro realizado com sucesso!', {
    description: `Bem-vindo(a), ${form.value.name}!`
  })

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
  }
}
</script>
