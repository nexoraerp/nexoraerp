import { defineStore } from 'pinia'

export const useAIStore = defineStore('ai', {

    state: () => ({

        open: false,

        loading: false,

        messages: [],

        suggestions: [

            "Bugünkü satışları analiz et",

            "Kritik stokları göster",

            "Finans özetini hazırla",

            "Ürün açıklaması oluştur"

        ],

        progress: 20

    }),

    actions: {

        toggle() {

            this.open = !this.open

        },

        close() {

            this.open = false

        }

    }

})