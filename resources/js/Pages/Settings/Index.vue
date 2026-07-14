<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { LockKeyhole, MailCheck, Plus, Trash2, UserPlus } from 'lucide-vue-next';

import NxLayout from '@/Layouts/NxLayout.vue';
import NxCard from '@/Components/UI/NxCard.vue';
import NxButton from '@/Components/UI/NxButton.vue';
import NxInput from '@/Components/UI/NxInput.vue';
import NxPageHeader from '@/Components/UI/NxPageHeader.vue';
import NxSelect from '@/Components/UI/NxSelect.vue';

const props = defineProps({
    subUsers: {
        type: Array,
        default: () => [],
    },
    permissions: {
        type: Object,
        default: () => ({}),
    },
    definitions: {
        type: Object,
        default: () => ({}),
    },
    definitionTypes: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const editingUser = ref(null);
const user = computed(() => page.props.auth?.user ?? {});

const activeSection = computed(() => {
    const query = page.url.split('?')[1] ?? '';
    const section = new URLSearchParams(query).get('section');

    return section ?? 'users';
});

const sectionLinks = [
    {
        key: 'users',
        title: 'Kullanıcılar',
        subtitle: 'Alt kullanıcıları oluşturun ve aktifliklerini yönetin.',
        href: '/settings?section=users',
    },
    {
        key: 'permissions',
        title: 'Yetkiler',
        subtitle: 'Kullanıcıların hangi modüllerde işlem yapabileceğini belirleyin.',
        href: '/settings?section=permissions',
    },
    {
        key: 'definitions',
        title: 'Ürün Tanımları',
        subtitle: 'Kategori, marka, model ve birim listelerini yönetin.',
        href: '/settings?section=definitions',
    },
    {
        key: 'security',
        title: 'Şifre ve Güvenlik',
        subtitle: 'Şifrenizi değiştirin ve doğrulama durumunu kontrol edin.',
        href: '/settings?section=security',
    },
];

const currentSection = computed(() => (
    sectionLinks.find(section => section.key === activeSection.value) ?? sectionLinks[0]
));

const permissionOptions = computed(() => Object.entries(props.permissions).map(([value, label]) => ({
    value,
    label,
})));

const definitionTypeOptions = computed(() => Object.entries(props.definitionTypes).map(([value, label]) => ({
    value,
    label,
})));

const userForm = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    permissions: [],
    is_active: true,
});

const definitionForm = useForm({
    type: 'category',
    name: '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const togglePermission = (permission) => {
    if (userForm.permissions.includes(permission)) {
        userForm.permissions = userForm.permissions.filter(item => item !== permission);
        return;
    }

    userForm.permissions = [...userForm.permissions, permission];
};

const resetUserForm = () => {
    editingUser.value = null;
    userForm.reset();
    userForm.clearErrors();
    userForm.permissions = [];
    userForm.is_active = true;
};

const editUser = (user) => {
    editingUser.value = user;
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.phone = user.phone ?? '';
    userForm.password = '';
    userForm.permissions = [...(user.permissions ?? [])];
    userForm.is_active = Boolean(user.is_active);
};

const saveUser = () => {
    if (editingUser.value) {
        userForm.put(route('settings.sub-users.update', editingUser.value.id), {
            preserveScroll: true,
            onSuccess: resetUserForm,
        });
        return;
    }

    userForm.post(route('settings.sub-users.store'), {
        preserveScroll: true,
        onSuccess: resetUserForm,
    });
};

const deleteUser = (user) => {
    router.delete(route('settings.sub-users.destroy', user.id), {
        preserveScroll: true,
    });
};

const saveDefinition = () => {
    definitionForm.post(route('settings.product-definitions.store'), {
        preserveScroll: true,
        onSuccess: () => definitionForm.reset('name'),
    });
};

const deleteDefinition = (definition) => {
    router.delete(route('settings.product-definitions.destroy', definition.id), {
        preserveScroll: true,
    });
};

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
        onError: () => passwordForm.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="currentSection.title" />

    <NxLayout>
        <div class="space-y-6">
            <NxPageHeader
                :title="currentSection.title"
                :subtitle="currentSection.subtitle"
            />

            <div class="grid gap-3 md:grid-cols-4">
                <Link
                    v-for="section in sectionLinks"
                    :key="section.key"
                    :href="section.href"
                    class="rounded-2xl border p-4 transition"
                    :class="activeSection === section.key ? 'border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-500/40 dark:bg-blue-950/40 dark:text-blue-100' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-slate-700'"
                >
                    <div class="font-black">{{ section.title }}</div>
                    <p class="mt-1 text-sm opacity-80">{{ section.subtitle }}</p>
                </Link>
            </div>

            <div
                v-if="['users', 'permissions'].includes(activeSection)"
                class="grid grid-cols-1 gap-6 2xl:grid-cols-3"
            >
                <NxCard class="2xl:col-span-1">
                    <div class="mb-5 flex items-center gap-3">
                        <UserPlus class="h-6 w-6 text-blue-700" />
                        <div>
                            <h2 class="text-xl font-black text-slate-950">
                                {{ editingUser ? 'Alt Kullanıcı Düzenle' : 'Alt Kullanıcı Ekle' }}
                            </h2>
                            <p class="text-sm text-slate-500">Erişebileceği modülleri seçin.</p>
                        </div>
                    </div>

                    <form
                        class="space-y-4"
                        @submit.prevent="saveUser"
                    >
                        <NxInput
                            v-model="userForm.name"
                            label="Ad Soyad"
                            :error="userForm.errors.name"
                        />

                        <NxInput
                            v-model="userForm.email"
                            label="E-posta"
                            :error="userForm.errors.email"
                        />

                        <NxInput
                            v-model="userForm.phone"
                            label="Telefon"
                            :error="userForm.errors.phone"
                        />

                        <NxInput
                            v-model="userForm.password"
                            type="password"
                            :label="editingUser ? 'Yeni Şifre (opsiyonel)' : 'Şifre'"
                            :error="userForm.errors.password"
                        />

                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3">
                            <input
                                v-model="userForm.is_active"
                                type="checkbox"
                                class="rounded border-slate-300 text-blue-700"
                            />
                            <span class="text-sm font-bold text-slate-700">Kullanıcı aktif</span>
                        </label>

                        <div>
                            <div class="mb-3 text-sm font-black text-slate-700">Yetkiler</div>

                            <div class="grid gap-2">
                                <label
                                    v-for="permission in permissionOptions"
                                    :key="permission.value"
                                    class="flex items-center gap-3 rounded-xl border border-slate-200 p-3"
                                >
                                    <input
                                        type="checkbox"
                                        class="rounded border-slate-300 text-blue-700"
                                        :checked="userForm.permissions.includes(permission.value)"
                                        @change="togglePermission(permission.value)"
                                    />
                                    <span class="text-sm font-semibold text-slate-700">{{ permission.label }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <NxButton type="submit">
                                {{ editingUser ? 'Güncelle' : 'Kullanıcı Ekle' }}
                            </NxButton>

                            <NxButton
                                v-if="editingUser"
                                type="button"
                                variant="secondary"
                                @click="resetUserForm"
                            >
                                Vazgeç
                            </NxButton>
                        </div>
                    </form>
                </NxCard>

                <NxCard class="2xl:col-span-2">
                    <div class="mb-5">
                        <h2 class="text-xl font-black text-slate-950">Alt Kullanıcılar</h2>
                        <p class="text-sm text-slate-500">Alt kullanıcılar ana hesabın verilerine yalnızca verilen yetkilerle erişir.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[760px]">
                            <thead class="border-b bg-slate-50 text-sm text-slate-500">
                                <tr>
                                    <th class="px-4 py-4 text-left">Kullanıcı</th>
                                    <th class="text-left">Yetkiler</th>
                                    <th class="text-center">Durum</th>
                                    <th class="pr-4 text-right">İşlem</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="user in subUsers"
                                    :key="user.id"
                                    class="border-b hover:bg-slate-50"
                                >
                                    <td class="px-4 py-4">
                                        <div class="font-black text-slate-950">{{ user.name }}</div>
                                        <div class="text-sm text-slate-500">{{ user.email }}</div>
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="permission in user.permissions"
                                                :key="permission"
                                                class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700"
                                            >
                                                {{ permissions[permission] ?? permission }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-bold"
                                            :class="user.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'"
                                        >
                                            {{ user.is_active ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </td>
                                    <td class="pr-4 text-right">
                                        <button
                                            type="button"
                                            class="rounded-xl px-3 py-2 text-sm font-bold text-blue-700 hover:bg-blue-50"
                                            @click="editUser(user)"
                                        >
                                            Düzenle
                                        </button>
                                        <button
                                            type="button"
                                            class="rounded-xl px-3 py-2 text-sm font-bold text-red-700 hover:bg-red-50"
                                            @click="deleteUser(user)"
                                        >
                                            Sil
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div
                            v-if="subUsers.length === 0"
                            class="rounded-b-2xl border-x border-b border-slate-100 bg-slate-50 p-8 text-center text-sm text-slate-500"
                        >
                            Henüz alt kullanıcı eklenmedi.
                        </div>
                    </div>
                </NxCard>
            </div>

            <NxCard v-if="activeSection === 'definitions'">
                <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-950">Ürün Tanımları</h2>
                        <p class="text-sm text-slate-500">Kategori, marka, model ve birim listeleri ürün formlarında dropdown olarak kullanılır.</p>
                    </div>

                    <form
                        class="grid gap-3 md:grid-cols-[220px_1fr_auto]"
                        @submit.prevent="saveDefinition"
                    >
                        <NxSelect
                            v-model="definitionForm.type"
                            label="Tanım Tipi"
                            :options="definitionTypeOptions"
                            option-label="label"
                            option-value="value"
                            :error="definitionForm.errors.type"
                        />

                        <NxInput
                            v-model="definitionForm.name"
                            label="Tanım"
                            :error="definitionForm.errors.name"
                        />

                        <NxButton
                            type="submit"
                            class="self-end"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Ekle
                        </NxButton>
                    </form>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div
                        v-for="(label, type) in definitionTypes"
                        :key="type"
                        class="rounded-2xl border border-slate-200 p-4"
                    >
                        <h3 class="font-black text-slate-950">{{ label }}</h3>

                        <div class="mt-4 space-y-2">
                            <div
                                v-for="definition in definitions[type] ?? []"
                                :key="definition.id"
                                class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 text-sm"
                            >
                                <span class="font-semibold text-slate-700">{{ definition.name }}</span>
                                <button
                                    type="button"
                                    class="text-red-700"
                                    @click="deleteDefinition(definition)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>

                            <div
                                v-if="!(definitions[type] ?? []).length"
                                class="rounded-xl bg-slate-50 px-3 py-4 text-sm text-slate-500"
                            >
                                Henüz tanım yok.
                            </div>
                        </div>
                    </div>
                </div>
            </NxCard>

            <div
                v-if="activeSection === 'security'"
                class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_420px]"
            >
                <NxCard>
                    <div class="mb-5 flex items-center gap-3">
                        <LockKeyhole class="h-6 w-6 text-blue-700" />
                        <div>
                            <h2 class="text-xl font-black text-slate-950">Şifre Değiştir</h2>
                            <p class="text-sm text-slate-500">
                                Hesabınız için güçlü ve yalnızca Nexora'da kullandığınız bir şifre belirleyin.
                            </p>
                        </div>
                    </div>

                    <form
                        class="grid gap-4 lg:grid-cols-3"
                        @submit.prevent="updatePassword"
                    >
                        <NxInput
                            v-model="passwordForm.current_password"
                            type="password"
                            label="Mevcut Şifre"
                            autocomplete="current-password"
                            :error="passwordForm.errors.current_password"
                        />

                        <NxInput
                            v-model="passwordForm.password"
                            type="password"
                            label="Yeni Şifre"
                            autocomplete="new-password"
                            :error="passwordForm.errors.password"
                        />

                        <NxInput
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            label="Yeni Şifre Tekrar"
                            autocomplete="new-password"
                            :error="passwordForm.errors.password_confirmation"
                        />

                        <div class="lg:col-span-3 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <NxButton
                                type="submit"
                                :disabled="passwordForm.processing"
                            >
                                Şifreyi Güncelle
                            </NxButton>

                            <p
                                v-if="passwordForm.recentlySuccessful"
                                class="text-sm font-semibold text-emerald-700"
                            >
                                Şifreniz güvenli şekilde güncellendi.
                            </p>
                        </div>
                    </form>
                </NxCard>

                <NxCard>
                    <div class="mb-5 flex items-center gap-3">
                        <MailCheck class="h-6 w-6 text-emerald-700" />
                        <div>
                            <h2 class="text-xl font-black text-slate-950">Hesap Doğrulama</h2>
                            <p class="text-sm text-slate-500">Kayıt ve oturum güvenliği durumu.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-black uppercase tracking-widest text-slate-500">
                                E-posta
                            </div>
                            <div class="mt-1 font-bold text-slate-950">{{ user.email }}</div>
                        </div>

                        <div
                            class="rounded-2xl border p-4"
                            :class="user.email_verified_at ? 'border-emerald-100 bg-emerald-50 text-emerald-800' : 'border-amber-100 bg-amber-50 text-amber-800'"
                        >
                            <div class="text-sm font-black">
                                {{ user.email_verified_at ? 'E-posta doğrulandı' : 'E-posta doğrulaması bekleniyor' }}
                            </div>
                            <p class="mt-1 text-sm opacity-80">
                                {{ user.email_verified_at ? 'ERP modülleri güvenli erişime açık.' : 'Doğrulama tamamlanmadan ERP modüllerine erişim kısıtlanır.' }}
                            </p>
                        </div>
                    </div>
                </NxCard>
            </div>
        </div>
    </NxLayout>
</template>
