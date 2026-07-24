document.addEventListener('alpine:init', () => {
    Alpine.data('treeCheckbox', (config) => ({
        options: config.options,
        selectedValues: config.selected,
        expanded: new Set(),
        flatTree: [],
        disabled: config.disabled,
        name: config.name,
        parentMap: {},
        childrenMap: {},

        init() {
            console.log(this.selectedValues);
            this.buildRelations(this.options, null);
            this.buildFlatTree();
            this.options.forEach(option => {
                option.children?.forEach(child => this.updateParents(child.value))
            })
        },

        buildRelations(items, parent = null) {
            items.forEach(item => {
                this.parentMap[item.value] = parent;
                this.childrenMap[item.value] = item.children
                    ? item.children.map(i => i.value)
                    : [];

                if (item.children?.length) {
                    this.buildRelations(item.children, item.value);
                }
            });
        },

        buildFlatTree() {
            this.flatTree = [];
            this.flattenTree(this.options, 0);
        },

        flattenTree(items, level) {
            items.forEach(item => {
                this.flatTree.push({
                    ...item,
                    level: level
                });

                if (this.isExpanded(item.value) && item.children) {
                    this.flattenTree(item.children, level + 1);
                }
            });
        },

        toggleExpand(value) {
            if (this.expanded.has(value)) {
                this.expanded.delete(value);
            } else {
                this.expanded.add(value);
            }
            this.buildFlatTree();
        },

        toggle(value, checked) {
            if (checked) {
                this.selectWithChildren(value);
            } else {
                this.unselectWithChildren(value);
            }

            this.updateParents(value);

            this.state = [...this.selectedValues];
        },

        selectWithChildren(value) {
            if (!this.selectedValues.includes(value)) {
                this.selectedValues.push(value);
            }

            (this.childrenMap[value] || []).forEach(child => {
                this.selectWithChildren(child);
            });
        },

        unselectWithChildren(value) {
            this.selectedValues =
                this.selectedValues.filter(v => v !== value);

            (this.childrenMap[value] || []).forEach(child => {
                this.unselectWithChildren(child);
            });
        },

        updateParents(value) {
            let parent = this.parentMap[value];

            while (parent !== null) {

                const children = this.childrenMap[parent];

                const allSelected =
                    children.every(c => this.selectedValues.includes(c));

                if (allSelected) {

                    if (!this.selectedValues.includes(parent)) {
                        this.selectedValues.push(parent);
                    }

                } else {

                    this.selectedValues =
                        this.selectedValues.filter(v => v !== parent);

                }

                parent = this.parentMap[parent];
            }
        },

        isChecked(value) {
            return this.selectedValues.includes(value);
        },

        isIndeterminate(value) {

            const children = this.childrenMap[value];

            if (!children.length) {
                return false;
            }

            const selected =
                children.filter(c => this.isChecked(c) || this.isIndeterminate(c));

            return (
                selected.length > 0 &&
                selected.length < children.length
            );
        },

        isExpanded(value) {
            return this.expanded.has(value);
        },

        hasChildren(item) {
            return item.children && item.children.length > 0;
        },
    }));
});
