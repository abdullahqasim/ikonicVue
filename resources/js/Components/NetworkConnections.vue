<template>
    <div class="row justify-content-center mt-5 dark">
      <div class="col-12">
        <div class="card shadow text-white bg-dark">
          <div class="card-header">Coding Challenge - Network connections</div>
          <div class="card-body">
            <div class="btn-group w-100 mb-3" role="group" aria-label="Basic radio toggle button group">

              <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" :checked="type === 'suggestions'">
              <NavLink :href="route('home', 'suggestions')" class="border border-blue-500 text-blue-500 hover:text-white hover:bg-blue-500 hover:border-transparent font-bold py-2 px-4 rounded" :class="{ 'btn-dark': type === 'suggestions' }" :for="btnradio1" id="get_suggestions_btn">
                Suggestions ({{ suggestions.total }})
              </NavLink>

              <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" :checked="type === 'sent'">
              <NavLink :href="route('home', 'sent')" class="border border-blue-500 text-blue-500 hover:text-white hover:bg-blue-500 hover:border-transparent font-bold py-2 px-4 rounded" :class="{ 'btn-dark': type === 'suggestions' }" :for="btnradio1" id="get_suggestions_btn">
                Sent Requests ({{ sendRequests.total }})
              </NavLink>

              <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" :checked="type === 'received'">
              <NavLink :href="route('home', 'received')" class="border border-blue-500 text-blue-500 hover:text-white hover:bg-blue-500 hover:border-transparent font-bold py-2 px-4 rounded" :class="{ 'btn-dark': type === 'suggestions' }" :for="btnradio1" id="get_suggestions_btn">
                Received Requests ({{ receivedRequests.total }})
              </NavLink>

              <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" :checked="type === 'connections'">
              <NavLink :href="route('home', 'connections')" class="border border-blue-500 text-blue-500 hover:text-white hover:bg-blue-500 hover:border-transparent font-bold py-2 px-4 rounded" :class="{ 'btn-dark': type === 'suggestions' }" :for="btnradio1" id="get_suggestions_btn">
                Connections ({{ connections.total }})
              </NavLink>
            </div>
            <hr>
            <div id="content" class="hidden">
            </div>

            <template v-if="type === 'sent'">
              <Request :mode="'sent'" :sendRequests="sendRequests.data" :receivedRequests="receivedRequests.data" />
              <div ref="skeleton_sent" class="hidden">
                <Skeleton v-for="i in 10" :key="i" />
              </div>

              <div class="flex justify-center mt-2 py-3" :class="{ 'hidden': sendRequests.last_page === sendRequests.current_page }" id="load_more_btn_parent_sent">
                <button class="bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-bold py-2 px-4 rounded" @click="loadMore(type)">Load more</button>
              </div>
            </template>

            <template v-if="type === 'received'">
              <Request :mode="'received'" :sendRequests="sendRequests.data" :receivedRequests="receivedRequests.data" />
              <div ref="skeleton_received" class="hidden">
                <Skeleton v-for="i in 10" :key="i" />
              </div>

              <div class="flex justify-center mt-2 py-3" :class="{ 'hidden': receivedRequests.last_page === receivedRequests.current_page }" id="load_more_btn_parent_received">
                <button class="bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-bold py-2 px-4 rounded" @click="loadMore(type)">Load more</button>
              </div>
            </template>

            <template v-if="type === 'suggestions'">
              <Suggestion :suggestions="suggestions.data" />
              <div ref="skeleton_suggestions" class="hidden">
                <Skeleton v-for="i in 10" :key="i" />
              </div>

              <div class="flex justify-center mt-2 py-3" :class="{ 'hidden': suggestions.last_page === suggestions.current_page }" id="load_more_btn_parent_suggestions">
                <button class="bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-bold py-2 px-4 rounded" @click="loadMore(type)">Load more</button>
              </div>
            </template>

            <template v-if="type === 'connections'">
              <Connection :connections="connections.data" :user="user" />
              <div ref="skeleton_connections" class="hidden">
                <Skeleton v-for="i in 10" :key="i" />
              </div>

              <div class="flex justify-center mt-2 py-3"
              :class="{ 'hidden': connections.last_page === connections.current_page }"
              id="load_more_btn_parent_connections">
                <button class="bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-bold py-2 px-4 rounded" @click="loadMore(type, user.id)">Load more</button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import Request from '@/components/Request.vue';
  import Suggestion from '@/components/Suggestion.vue';
  import Connection from '@/components/Connection.vue';
  import Skeleton from '@/components/Skeleton.vue';
  import NavLink from '@/components/NavLink.vue';
  import ConnectionInCommon from '@/components/ConnectionInCommon.vue';
  import axios from 'axios';

  export default {

    mounted() {
        this.type = this.$page.props.type;
        this.user.id = this.$page.props.auth.user.id;
        this.connections = this.$page.props.connections;
        this.receivedRequests = this.$page.props.receivedRequests;
        this.sendRequests = this.$page.props.sendRequests;
        this.suggestions = this.$page.props.suggestions;
    },

    data() {
      return {
        type: 'suggestions',
        sendRequests: [],
        receivedRequests: [],
        suggestions: [],
        connections: [],
        user: {
          id: 1 // Replace with the actual user ID
        },
        page: 1,
        hasMore: true
      };
    },
    components: {
      Request,
      Suggestion,
      Connection,
      Skeleton,
      NavLink,
      ConnectionInCommon,
    },

    methods: {
        withdrawRequest(id, type) {
            axios.delete(`/requests/${id}`)
            .then(res => {
                router.visit('/dashboard/sent');
            })
            .catch(error => {
                // Handle error
            });
            this.$emit('withdraw-request', id, type);
        },
      loadMore(type) {
        if (this.hasMore) {
            const skeletonId = `skeleton_${type}`;
            this.$refs[skeletonId].classList.remove('hidden');
            const url = '/requests';
            const data = {
                page: this.page + 1,
                type: type,
            };

            axios
                .get(url, { params: data })
                .then((response) => {
                this.$refs['skeleton_' + type].classList.add('hidden');

                let e = '';
                if (response.data.data.length) {
                    response.data.data.forEach((val) => {
                    if (type === 'sent') {
                        e += `
                        <div id="${type}_${val.id}" class="flex justify-between">
                            <table class="ms-1">
                            <td class="items-center">${val.receiver[0].name}</td>
                            <td class="items-center"> - </td>
                            <td class="items-center">${val.receiver[0].email}</td>
                            <td class="items-center"></td>
                            </table>
                            <div>
                            <button id="cancel_request_btn_" class="bg-red-500 text-white hover:bg-red-600 focus:bg-red-600 px-4 py-2 rounded-md mr-1" onclick="withdrawRequest(${val.id})">Withdraw Request</button>
                            </div>
                        </div>
                        `;
                    }
                    if (type === 'received') {
                        e += `
                        <div class="flex justify-between">
                            <table class="ms-1">
                            <td class="items-center">${val.name}</td>
                            <td class="items-center"> - </td>
                            <td class="items-center">${val.email}</td>
                            <td class="items-center"></td>
                            </table>
                            <div>
                            <button id="accept_request_btn_" class="bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-bold py-2 px-4 rounded me-1" onclick="">Accept</button>
                            </div>
                        </div>
                        `;
                    }
                    if (type === 'suggestions') {
                        e += `
                        <div class="flex justify-between">
                            <table class="ms-1">
                            <td class="items-center">${val.name}</td>
                            <td class="items-center"> - </td>
                            <td class="items-center">${val.email}</td>
                            <td class="items-center"></td>
                            </table>
                            <div>
                            <button onclick="handleAction(${val.id})" id="create_request_btn_" class="bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-bold py-2 px-4 rounded me-1">Connect</button>
                            </div>
                        </div>
                        `;
                    }
                    if (type === 'connections') {
                        let connectionsInCommon = val.commonConnections.data.length;
                        let userField = this.user.id != val.sender[0].id ? val.sender[0] : val.receiver[0];

                        e += `
                        <div class="flex justify-between">
                            <table class="ms-1">
                            <td class="items-center">${userField.name}</td>
                            <td class="items-center"> - </td>
                            <td class="items-center">${userField.email}</td>
                            <td class="items-center"></td>
                            </table>
                            <div>
                            <div>
                                <button style="width: 220px" id="get_connections_in_common_"
                                class="bg-blue-500 text-white hover:bg-blue-600 focus:bg-blue-600 px-4 py-2 rounded-md mx-2
bg-blue-500 text-white hover:bg-blue-600 focus:bg-blue-600 px-4 py-2 rounded-md mx-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_" aria-expanded="false" aria-controls="collapseExample">Connections in common (${connectionsInCommon})</button>
                                <button id="create_request_btn_" onclick="withdrawRequest(${val.id},'connection')"
                                class="bg-red-500 text-white hover:bg-red-600 focus:bg-red-600 px-4 py-2 rounded-md mr-1">Remove Connection</button>
                            </div>
                            </div>
                        </div>
                        `;
                    }
                    });
                }
                document.getElementById(type).insertAdjacentHTML('beforeend', e);

                this.page += 1;
                if (response.data.last_page === this.page) {
                    this.hasMore = false;
                    document.getElementById('load_more_btn_parent_' + type).classList.add('hidden');
                }
                })
                .catch((error) => {
                this.$refs['skeleton_' + type].classList.add('hidden');
                });



          // Example:
          // this.sendRequests = ...;
          // this.receivedRequests = ...;
          // this.suggestions = ...;
          // this.connections = ...;
          // this.page = ...;
          // this.hasMore = ...;

          this.$refs[skeletonId].classList.add('hidden');
        }
      },
      getRoute(type) {
        // Replace with your actual route generation logic
        // Example: return `/home?type=${type}`;
        return '';
      }
    }
  };
  </script>

  <style scoped>
  /* Add your custom styles here */
  </style>
