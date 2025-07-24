import type { SharedData } from '@/types';
import { usePage as useInertiaPage } from '@inertiajs/vue3';

export function usePage() {
    return useInertiaPage<SharedData>();
}

export function useAuth() {
    const page = usePage();
    return page.props.auth || { user: null };
}

export function useUser() {
    const auth = useAuth();
    return auth.user;
}
