<template>
  <div>
    <div class="rframe">
      <v-container v-if="!adminData">
        <v-row>
          <v-col cols="12" md="6" offset-md="3">
            <v-card>
              <v-card-text>
                <h4>Please Login To Continue...</h4>
                <br />
                <v-btn dark to="/admin/login">
                  <span>Login</span>
                  <v-icon>login</v-icon>
                </v-btn>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </div>
    <v-container>
      <v-row>
        <v-col cols="12" md="12" sm="12">
          <v-card dark ref="top">
            <v-toolbar>
              <v-btn :to="`/admin/manga/volume/episodes/${$route.params.vid}/${$route.params.mid}/${$route.params.aid}`">
                <v-icon>arrow_back</v-icon>
              </v-btn>
              <v-spacer></v-spacer>
              <span>Pages:{{count}}</span>
              <v-spacer></v-spacer>
              <v-sheet @click="goViewRoom">
                <v-btn
                  
                  :to="`/admin/manga/volume/images/${eid}/${$route.params.mid}/${$route.params.vid}/${$route.params.aid}/mangaRoom`"
                  >Slide View</v-btn
                >
              </v-sheet>
            </v-toolbar>
            <v-tabs
              background-color="cyan"
              dark
              next-icon="mdi-arrow-right-bold-box-outline"
              prev-icon="mdi-arrow-left-bold-box-outline"
              show-arrows
              v-model="model"
            >
              <v-tabs-slider color="yellow"></v-tabs-slider>
              <v-tab
                v-for="i in allEpisodes"
                :key="i.id"
                :href="`#tab-${i.id}`"
                @click=" getDynamicEpisode(i.id)"
              >
                {{ i.episode_name }}
              </v-tab>
            </v-tabs>

            <v-card-text class="text-center">
              <div class="rtl">
                <v-btn
                  small
                  fixed
                  bottom
                  outlined
                  text
                  @click="scrollTop('top')"
                >
                  <v-icon color="blue">arrow_upward</v-icon>
                </v-btn>
              </div>
              <div v-for="(img, index) in multiImages" :key="index">
                <img
                  class="mt-3 img"
                  :src="`/manga/${$route.params.mid}/${$route.params.vid}/${eid}/${img}`"
                  alt=""
                />
                <v-divider></v-divider>
              </div>
            </v-card-text>
            <v-card-actions class="justify-end">
              <v-btn @click="scrollTop('top')">up</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import axios from "axios";
import { eventBus } from "../../app";
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      allEpisodes: [],
      multiImages: [],
      eid: null,
      model:`tab-${this.$route.params.eid}`,
      count:0,
    };
  },
  methods: {
    async getEpisodeByVid() {
      let vid = this.$route.params.vid;
      await axios
        .get(`/admin/manga/volume/getEpisodeByVid/${vid}`)
        .then((resp) => {
          this.allEpisodes = resp.data;
        });
    },
    async getMultiImages() {
      let epid = this.$route.params.eid;
      await axios
        .get(`/admin/manga/volume/multiImages/${epid}`)
        .then((resp) => {
          this.multiImages = resp.data.data;
          this.eid = JSON.parse(resp.data.id);
          this.count=resp.data.count;
          this.model=`tab-${this.eid}`
        });
    },
    async getDynamicEpisode(id) {
      await axios
        .get(`/admin/manga/volume/dynamicImages/${id}`)
        .then((resp) => {
          this.multiImages = resp.data.data;
          this.eid = id;
          this.model=`tab-${this.eid}`
          this.count=resp.data.count;
          this.$router.push(`/admin/manga/volume/images/${this.eid}/${this.$route.params.mid}/${this.$route.params.vid}/${this.$route.params.aid}`)
        });
    },
    goMangaRoom(id) {
      this.$router.push(
        `/admin/manga/volume/mangaRoom/${id}/${this.$route.params.eid}`
      );
    },
    goViewRoom() {
      eventBus.$emit("wide");
    },
    scrollTop(refName) {
      var element = this.$refs[refName];
      var top = element.offsetTop;
      window.scrollTo(0, top);
    },
    // async nextEpisode(id)
    // {
    //   let eid=this.$route.params.eid;
    //    console.log(+eid+1);
    // }
  },
  computed: {
    ...mapGetters(["adminData"]),
  },
  mounted() {
    // this.nextEpisode();
    this.getEpisodeByVid();
    this.getMultiImages();
    eventBus.$emit("hideDrawer");
    eventBus.$emit("show");
  },
};
</script>

<style lang="scss" scoped>
.img {
  width: 728px;
  height: 1046px;
  cursor: pointer;
  border: 5px solid rgb(0, 0, 0);
}
.rtl {
  direction: rtl;
  margin-left: 1000px;
}
.rframe {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
@media only screen and (max-width: 600px) {
  .img {
    max-width: 345px;
    max-height: 500px;
    cursor: pointer;
  }
  .rtl {
    direction: rtl;
    margin-left: 350px;
  }
}
@media only screen and (min-width: 750px) and (max-width: 950px) {
  .img {
    max-width: 550px;
    max-height: 700px;
    cursor: pointer;
  }
  .rtl {
    direction: rtl;
    margin-left: 750px;
  }
}
@media only screen and (min-width: 600px) and (max-width: 745px) {
  .img {
    max-width: 480px;
    max-height: 630px;
    cursor: pointer;
  }
  .rtl {
    direction: rtl;
    margin-left: 570px;
  }
}
@media only screen and (min-width: 350px) and (max-width: 380px) {
  .img {
    max-width: 300px;
    max-height: 450px;
    cursor: pointer;
  }
}
</style>