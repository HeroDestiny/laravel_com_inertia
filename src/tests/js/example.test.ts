import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import { ref } from 'vue';

// Exemplo de componente simples para teste
const ExampleComponent = {
    template: '<div>{{ message }}</div>',
    props: ['message'],
};

describe('Example Component Tests', () => {
    it('renders message prop correctly', () => {
        const wrapper = mount(ExampleComponent, {
            props: { message: 'Hello World' },
        });

        expect(wrapper.text()).toBe('Hello World');
    });

    it('reactive data works correctly', () => {
        const count = ref(0);

        expect(count.value).toBe(0);

        count.value++;

        expect(count.value).toBe(1);
    });
});

// Exemplo de função utilitária para teste
export function formatCurrency(value: number): string {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value);
}

describe('Utility Functions', () => {
    it('formats currency correctly', () => {
        expect(formatCurrency(1000)).toBe('R$ 1.000,00');
        expect(formatCurrency(0)).toBe('R$ 0,00');
        expect(formatCurrency(10.5)).toBe('R$ 10,50');
    });
});
