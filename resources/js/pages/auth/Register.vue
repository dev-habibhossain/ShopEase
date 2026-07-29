<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { User, Upload } from '@lucide/vue';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    },
});

const avatarPreview = ref<string | null>(null);

const form = useForm({
    name: '',
    email: '',
    role: 'customer',
    password: '',
    password_confirmation: '',
    avatar: null as File | null,
});

const handleAvatarChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register — ShopEase" />

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-6">
            <!-- Profile Photo Upload -->
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="relative group">
                    <div class="h-20 w-20 rounded-full bg-violet-50 border-2 border-violet-200 overflow-hidden flex items-center justify-center shadow-sm">
                        <img v-if="avatarPreview" :src="avatarPreview" class="h-full w-full object-cover" />
                        <User v-else class="w-10 h-10 text-violet-600" />
                    </div>
                    <label class="absolute bottom-0 right-0 bg-violet-600 text-white rounded-full p-1.5 shadow-md cursor-pointer hover:bg-violet-700 transition">
                        <Upload class="w-3.5 h-3.5" />
                        <input type="file" accept="image/*" class="hidden" @change="handleAvatarChange" />
                    </label>
                </div>
                <p class="text-[11px] text-gray-500 font-medium">Upload profile picture (optional)</p>
            </div>

            <!-- Role Selector -->
            <div class="grid gap-2">
                <Label>Account Type (Role)</Label>
                <div class="grid grid-cols-3 gap-2">
                    <button
                        type="button"
                        @click="form.role = 'customer'"
                        :class="[
                            'px-3 py-2 text-xs font-semibold rounded-lg border transition text-center',
                            form.role === 'customer'
                                ? 'bg-violet-50 border-violet-600 text-violet-700 shadow-sm'
                                : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        Customer
                    </button>
                    <button
                        type="button"
                        @click="form.role = 'admin'"
                        :class="[
                            'px-3 py-2 text-xs font-semibold rounded-lg border transition text-center',
                            form.role === 'admin'
                                ? 'bg-violet-50 border-violet-600 text-violet-700 shadow-sm'
                                : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        Admin
                    </button>
                    <button
                        type="button"
                        @click="form.role = 'super_admin'"
                        :class="[
                            'px-3 py-2 text-xs font-semibold rounded-lg border transition text-center',
                            form.role === 'super_admin'
                                ? 'bg-violet-50 border-violet-600 text-violet-700 shadow-sm'
                                : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'
                        ]"
                    >
                        Super Admin
                    </button>
                </div>
                <InputError :message="form.errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    placeholder="Full name"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    placeholder="email@example.com"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    placeholder="Password"
                    :passwordrules="passwordRules"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm password</Label>
                <PasswordInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    placeholder="Confirm password"
                    :passwordrules="passwordRules"
                />
                <InputError :message="form.errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full bg-violet-600 hover:bg-violet-700 text-white"
                tabindex="5"
                :disabled="form.processing"
                data-test="register-user-button"
            >
                <Spinner v-if="form.processing" />
                Create account
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink
                :href="login()"
                class="underline underline-offset-4 text-violet-600 hover:text-violet-700"
                :tabindex="6"
                >Log in</TextLink
            >
        </div>
    </form>
</template>
