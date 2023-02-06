<template>
    <button :type="type" :class="classList" class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm  disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'button',
            },

            classList: {
                    type: String,
                    default:'bg-white border border-gray-300 hover:text-gray-500 text-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50'
            }
        }
    }
</script>
