<template>
    <div class="my-2 shadow text-white bg-dark p-1" id="connections">
        <template v-for="connection in connections">
            <template v-if="user.id !== connection.sender[0].id">
                <div :key="connection.id" class="flex justify-between">
                        <table class="ms-1">
                            <td class="items-center">{{ connection.sender[0].name }}</td>
                            <td class="items-center"> - </td>
                            <td class="items-center">{{ connection.sender[0].email }}</td>
                            <td class="items-center"></td>
                        </table>
                        <button
                            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            type="button"
                            data-te-collapse-init
                            data-te-ripple-init
                            data-te-ripple-color="light"
                            :data-te-target="'#collapse_' + connection.id"
                            aria-expanded="false"
                            :aria-controls="'collapse_' + connection.id">
                            Connections in common ({{ connection.commonConnections.total }})
                            </button>
                        <div>
                            <button :id="'create_request_btn_' + connection.id"
                                @click="withdrawRequest(connection.id, 'connection')"
                                class="bg-red-500 text-white hover:bg-red-600 focus:bg-red-600 px-4 py-2 rounded-md mr-1">
                                Remove Connection
                            </button>
                        </div>
                    </div>
                    <div class="!visible hidden" data-te-collapse-item :id="'collapse_' + connection.id">
                        <div :id="'content_' + connection.id" class="p-2">
                            <ConnectionInCommon :connection="connection.commonConnections.data"></ConnectionInCommon>
                        </div>
                        <div :ref="'connections_in_common_skeletons_' + connection.id" class="hidden">
                            <Skeleton v-for="i in 10" :key="i" />
                        </div>
                        <div class="d-flex justify-content-center w-100 py-2"
                            :class="{ 'hidden': connection.commonConnections.last_page === connection.commonConnections.current_page }"
                            :id="'load_more_' + connection.id">
                            <button class="btn btn-sm btn-primary" :id="'load_more_connections_in_common_' + connection.id"
                                @click="loadMoreCommon(connection.sender[0].id, connection.id)">
                                Load more
                            </button>
                        </div>
                    </div>
            </template>

            <template v-else>
                <div :key="connection.id" class="flex justify-between">
                        <table class="ms-1">
                            <td class="items-center">{{ connection.receiver[0].name }}</td>
                            <td class="items-center"> - </td>
                            <td class="items-center">{{ connection.receiver[0].email }}</td>
                            <td class="items-center"></td>
                        </table>
                        <div>
                            <button
                            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            type="button"
                            data-te-collapse-init
                            data-te-ripple-init
                            data-te-ripple-color="light"
                            :data-te-target="'#collapse_' + connection.id"
                            aria-expanded="false"
                            :aria-controls="'collapse_' + connection.id">
                            Connections in common ({{ connection.commonConnections.total }})
                            </button>

                            <button :id="'create_request_btn_' + connection.id"
                                @click="withdrawRequest(connection.id, 'connection')"
                                class="bg-red-500 text-white hover:bg-red-600 focus:bg-red-600 px-4 py-2 rounded-md mr-1">
                                Remove Connection
                            </button>
                        </div>
                    </div>
                    <div class="!visible hidden" data-te-collapse-item :id="'collapse_' + connection.id">
                        <div :id="'content_' + connection.id" class="p-2">
                            <ConnectionInCommon :connection="connection.commonConnections.data"></ConnectionInCommon>
                        </div>
                        <div :id="'connections_in_common_skeletons_' + connection.id" :ref="'connections_in_common_skeletons_' + connection.id" class="hidden">
                            <Skeleton v-for="i in 10" :key="i" />
                        </div>
                        <div class="d-flex justify-content-center w-100 py-2"
                            :class="{ 'hidden': connection.commonConnections.last_page === connection.commonConnections.current_page }"
                            :id="'load_more_' + connection.id">
                            <button class="btn btn-sm btn-primary" :id="'load_more_connections_in_common_' + connection.id"
                                @click="loadMoreCommon(connection.receiver[0].id, connection.id)">
                                Load more
                            </button>
                        </div>
                    </div>
            </template>
        </template>
    </div>
</template>

<script>
import ConnectionInCommon from '@/components/ConnectionInCommon.vue';
import Skeleton from '@/components/Skeleton.vue';
import {
  Collapse,
  Ripple,
  initTE,
} from "tw-elements";


export default {
    data() {
      return {
        page_numbers : [],
        have_more : [],
      };
    },
    components: {
        ConnectionInCommon,
        Skeleton,
    },
    props: {
        connections: {
            type: Array,
            default: () => []
        },
        user: {
            type: Object
        }
    },
    mounted(){
        initTE({ Collapse, Ripple });
    },
    methods: {
        withdrawRequest(id, type) {
            axios.delete(`/requests/${id}`)
            .then(res => {
                if (type) {
                window.location.replace('/dashboard/connections');
                } else {
                window.location.replace('dashboard/received');
                }
            })
            .catch(error => {
                // Handle error
            });
            this.$emit('withdraw-request', id, type);
        },
        loadMoreCommon(userId, id) {
            if (this.page_numbers[id] == undefined) {
                this.page_numbers[id] = 1;
                this.have_more[id] = true;
            }
            if (this.have_more[id]) {

                const skeletonId = `connections_in_common_skeletons_${id}`;

                // this.$refs[skeletonId].classList.remove('hidden');
                const el = document.querySelector(`#${skeletonId}`);
                el.classList.remove("hidden");

                axios.get('/requests', {
                    params: {
                        page: this.page_numbers[id] + 1,
                        user_id: userId,
                        type: 'common-connections'
                    }
                })
                .then((response) => {
                    el.classList.add("hidden")
                    // this.$refs[skeletonId].classList.add('d-none');

                    const newData = response.data.data;
                    var e = '';
                    if (newData.length) {
                        newData.forEach(val => {
                            e += '<div class="p-2 shadow rounded mt-2 text-white bg-dark">' +
                                val.name + ' - ' + val.email + '</div>';
                        });
                    // this.connections[id].commonConnections.data.push(...newData);
                    }
                    var contentId = 'content_' + id;
                    document.getElementById(contentId).insertAdjacentHTML('beforeend', e);
                    // $('#content_' + id).append(e);

                    this.page_numbers[id] += 1;
                    if (response.data.last_page === this.page_numbers[id]) {
                    this.have_more[id] = false;
                    const loadMoreId = `load_more_${id}`;
                    el.classList.add("hidden");
                    }
                })
                .catch((error) => {
                    el.classList.add("hidden")
                    // this.$refs[skeletonId].classList.add('d-none');
                    console.error(error);
                });
            }
            this.$emit('load-more-common', userId, id);
        }
    }
};
</script>

<style scoped>
.justify-content-between {
    justify-content: space-between !important;
    margin: 7px !important;
}
</style>
