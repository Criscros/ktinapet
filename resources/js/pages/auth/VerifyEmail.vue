<script setup lang="ts">
import EmailVerificationNotificationController from '@/actions/App/Http/Controllers/Auth/EmailVerificationNotificationController';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Verifica tu correo"
        description="Por favor verifica tu correo haciendo clic en el enlace que te acabamos de enviar."
    >
        <Head title="Verificación de correo" />

        <div
            v-if="status === 'verification-link-sent'"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            Se ha enviado un nuevo enlace de verificación al correo que
            proporcionaste durante el registro.
        </div>

        <Form
            v-bind="EmailVerificationNotificationController.store.form()"
            class="space-y-6 text-center"
            v-slot="{ processing }"
        >
            <Button :disabled="processing" variant="secondary">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Reenviar correo de verificación
            </Button>

            <TextLink
                :href="logout()"
                as="button"
                class="mx-auto block text-sm"
            >
                Cerrar sesión
            </TextLink>
        </Form>
    </AuthLayout>
</template>
