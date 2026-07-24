@php
    $options = $getOptions();
    $selected = $getState() ?? [];
    $isDisabled = $isDisabled();
    $name = $getId();
    $isRequired = $isRequired();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="treeCheckbox({
            options: @js($options),
            selected: @js($selected),
            name: @js($name),
            disabled: @js($isDisabled)
        })"
        x-init="init()"
        class="space-y-2 rtl"
    >
        <template x-for="(item, index) in flatTree" :key="item.value">
            <div
                class="fi-fo-checkbox-list-option flex items-center gap-x-2 rounded-lg"
                :style="`padding-inline-start: ${item.level * 24}px`"
            >
                <x-filament::input.checkbox
                    x-bind:id="`${name}-${item.value}`"
                    x-bind:value="item.value"
                    x-bind:checked="isChecked(item.value)"
                    x-bind:indeterminate="isIndeterminate(item.value)"
                    @change="toggle(item.value, $event.target.checked)"
                    x-bind:disabled="disabled"
                    x-data="{state: $wire.$entangle('{{ $getStatePath() }}')}"
                />

                <span
                    x-show="hasChildren(item)"
                    @click="toggleExpand(item.value)"
                    class="cursor-pointer text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 text-xs ps-2"
                    x-text="isExpanded(item.value) ? '▼' : '◀'"
                ></span>

                <label
                    :for="`${name}-${item.value}`"
                    class="text-sm font-medium fi-fo-field-wrp-label cursor-pointer ps-2"
                >
                    <span x-text="item.label+item.value"></span>
                </label>
            </div>
        </template>
    </div>
</x-dynamic-component>
