import { ref, computed } from "vue";
import { showcaseItems } from "./showcaseData";

const active = ref("dashboard");

export function useShowcase() {

    const current = computed(() => {

        return showcaseItems.find(
            item => item.id === active.value
        );

    });

    return {

        active,

        current,

        showcaseItems

    };

}