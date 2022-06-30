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
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.container {
  width: clamp(40%, 300px);
  height: 560px;
  overflow: auto;
}
</style>
