<template>
  <div>
    <div class="container">
      <tree-menu
        ref="menu"
        :label="tree.label"
        :nodes="tree.nodes"
        :depth="0"
      ></tree-menu>
    </div>
  </div>
</template>

<script>
import TreeMenu from "./TreeMenu";

export default {
  data() {
    return {
      tree: {
        label: "root",
        nodes: [
          {
            label: "item1",
            nodes: [
              {
                label: "item1.1",
              },
              {
                label: "item1.2",
                nodes: [
                  {
                    label: "root",
                    nodes: [
                      {
                        label: "item1",
                        nodes: [
                          {
                            label: "item1.1",
                          },
                          {
                            label: "item1.2",
                            nodes: [
                              {
                                label: "root",
                                nodes: [
                                  {
                                    label: "item1",
                                    nodes: [
                                      {
                                        label: "item1.1",
                                      },
                                      {
                                        label: "item1.2",
                                        nodes: [
                                          {
                                            label: "item1.2.1",
                                          },
                                        ],
                                      },
                                    ],
                                  },
                                  {
                                    label: "item2",
                                  },
                                ],
                              },
                            ],
                          },
                        ],
                      },
                      {
                        label: "root",
                        nodes: [
                          {
                            label: "item1",
                            nodes: [
                              {
                                label: "item1.1",
                              },
                              {
                                label: "item1.2",
                                nodes: [
                                  {
                                    label: "item1.2.1",
                                  },
                                ],
                              },
                            ],
                          },
                          {
                            label: "root",
                            nodes: [
                              {
                                label: "item1",
                                nodes: [
                                  {
                                    label: "item1.1",
                                  },
                                  {
                                    label: "item1.2",
                                    nodes: [
                                      {
                                        label: "item1.2.1",
                                      },
                                    ],
                                  },
                                ],
                              },
                              {
                                label: "item2",
                              },
                            ],
                          },
                        ],
                      },
                    ],
                  },
                ],
              },
            ],
          },
          {
            label: "root",
            nodes: [
              {
                label: "item1",
                nodes: [
                  {
                    label: "item1.1",
                  },
                  {
                    label: "item1.2",
                    nodes: [
                      {
                        label: "item1.2.1",
                      },
                    ],
                  },
                ],
              },
              {
                label: "item2",
              },
            ],
          },
        ],
      },
    };
  },

  components: {
    TreeMenu
  },
};
</script>

<style scoped>
:root {
  --scrollbarBg: rgba(2, 142, 74, 1);
  --scrollbarThumb: rgba(255, 91, 91, 0.8);
  --scrollbarWidth: 12px;
  --scrollbarBorder: 3px solid var(--scrollbarBg);
  --scrollbarBorderRadius: calc(var(--scrollbarWidth) / 2);
}
.container {
  scrollbar-width: var(--scrollbarWidth);
  scrollbar-color: var(--scrollbarThumb) var(--scrollbarBg);
}
.container::-webkit-scrollbar {
  width: var(--scrollbarWidth);
}
.container::-webkit-scrollbar-track {
  background: var(--scrollbarBg);
  border-radius: var(--scrollbarBorderRadius);
}
.container::-webkit-scrollbar-thumb {
  background-color: var(--scrollbarThumb);
  border-radius: var(--scrollbarBorderRadius);
  border: var(--scrollbarBorder);
}
.container {
  width: clamp(50%, 300px);
  height: 600px;
  overflow: auto;
}
</style>
