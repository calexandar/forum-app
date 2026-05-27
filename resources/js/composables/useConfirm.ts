import { reactive, readonly } from 'vue';

const globalState = reactive({
    show: false,
    title: '',
    message: '',
    resolver: null as ((value: boolean) => void) | null,
});

export function useConfirm() {
    const resetModal = () => {
        globalState.show = false;
        globalState.title = '';
        globalState.message = '';
        globalState.resolver = null;
    };

    return {
        state: readonly(globalState),
        confirmation(title: string, message: string): Promise<boolean> {
            globalState.title = title;
            globalState.message = message;
            globalState.show = true;

            return new Promise((resolve) => {
                globalState.resolver = resolve;
            });
        },
        confirm(value: boolean) {
            if (globalState.resolver) {
                globalState.resolver(value);
            }
            globalState.show = false;
        },
        cancel(value: boolean) {
            if (globalState.resolver) {
                globalState.resolver(value);
            }
            globalState.show = false;

            resetModal()
        }
    };
}