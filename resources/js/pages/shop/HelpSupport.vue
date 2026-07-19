<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const faqs = ref([
    {
        question: 'How long does delivery take?',
        answer: 'Standard delivery typically takes 2-4 business days within major cities, and 4-7 business days for regional areas. Express delivery options are available at checkout.',
        open: false,
    },
    {
        question: 'What is your return policy?',
        answer: 'We offer a 7-day return policy for unused products in their original packaging. Please contact our support team to initiate a return or exchange.',
        open: false,
    },
    {
        question: 'How can I track my order?',
        answer: 'Once your order is shipped, you will receive a tracking link via SMS and email. You can also view order status directly under your account dashboard.',
        open: false,
    },
    {
        question: 'Do you offer cash on delivery (COD)?',
        answer: 'Yes! We support Cash on Delivery across Bangladesh, alongside secure online payments using Stripe (Visa, Mastercard, American Express).',
        open: false,
    },
]);

const contactForm = ref({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const isSubmitted = ref(false);

const submitForm = () => {
    // Simple frontend simulation of contact submission
    isSubmitted.value = true;
    contactForm.value = {
        name: '',
        email: '',
        subject: '',
        message: '',
    };
    setTimeout(() => {
        isSubmitted.value = false;
    }, 5000);
};
</script>

<template>
    <Head title="Help & Support - ShopEase" />

    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Help & Support
            </h1>
            <p class="mt-4 text-base text-gray-500">
                Have questions or need assistance? We are here to help you 24/7. Browse our FAQs or reach out directly to our customer support team.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
            <!-- Left Side: FAQs -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <div
                        v-for="(faq, index) in faqs"
                        :key="index"
                        class="rounded-xl border border-gray-200 bg-white p-5 transition shadow-sm hover:border-gray-300"
                    >
                        <button
                            @click="faq.open = !faq.open"
                            type="button"
                            class="flex w-full items-center justify-between text-left focus:outline-none"
                        >
                            <span class="text-base font-semibold text-gray-900">
                                {{ faq.question }}
                            </span>
                            <span class="ml-6 flex h-7 items-center">
                                <svg
                                    class="h-5 w-5 transform text-gray-400 transition-transform duration-200"
                                    :class="{ 'rotate-180': faq.open }"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </span>
                        </button>
                        <div v-show="faq.open" class="mt-3 text-sm text-gray-600 leading-relaxed">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>

                <!-- Live Contact Cards -->
                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-gray-50 p-5 border border-gray-100 flex items-start gap-4">
                        <div class="rounded-lg bg-primary-50 p-2.5 text-primary-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Call Us</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Mon-Sat, 9AM-6PM</p>
                            <p class="text-sm font-semibold text-primary-600 mt-1">+880 1700-000000</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-5 border border-gray-100 flex items-start gap-4">
                        <div class="rounded-lg bg-primary-50 p-2.5 text-primary-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Email Us</h3>
                            <p class="text-xs text-gray-500 mt-0.5">We reply within 24 hours</p>
                            <p class="text-sm font-semibold text-primary-600 mt-1">support@shopease.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Contact Form -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                <form @submit.prevent="submitForm" class="space-y-5">
                    <div v-if="isSubmitted" class="rounded-lg bg-green-50 p-4 text-sm font-medium text-green-700">
                        Thank you! Your message has been sent successfully. We will get back to you soon.
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <input
                            id="name"
                            type="text"
                            required
                            v-model="contactForm.name"
                            class="mt-1.5 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none"
                            placeholder="John Doe"
                        />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            required
                            v-model="contactForm.email"
                            class="mt-1.5 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none"
                            placeholder="john@example.com"
                        />
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-gray-700">Subject</label>
                        <input
                            id="subject"
                            type="text"
                            required
                            v-model="contactForm.subject"
                            class="mt-1.5 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none"
                            placeholder="How can we help you?"
                        />
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700">Message</label>
                        <textarea
                            id="message"
                            rows="4"
                            required
                            v-model="contactForm.message"
                            class="mt-1.5 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-primary-600 focus:bg-white focus:ring-2 focus:ring-primary-600 focus:outline-none"
                            placeholder="Type your message here..."
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-primary-600 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:outline-none"
                    >
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </main>
</template>

<script lang="ts">
import ShopLayout from '@/layouts/ShopLayout.vue';

export default {
    layout: ShopLayout,
};
</script>
