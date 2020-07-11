<template>
    <div>
        <button class=" btn btn-primary ml-4" @click=" followUser" v-text="buttonText"></button>
<!--
    ////followUser: is a methods
-->
    </div>
</template>

<script>
    export default {
        props: ['userId','follows'],
        //props->additional data field
        mounted()
         {
            console.log('Component mounted.')
        },
        data:function()
        {
            return {
                status: this.follows,
            }
        },
        //data is attribute

        methods:
        {
            followUser()
            {
               // alert('inside');
               axios.post('/follow/' + this.userId)
               //axios make a post request to (route)
               .then(response=>
               {
                   //when get a successful response than change status
                   this.status= !this.status;
                   console.log(response.data);

               })
               .catch(errors=>
               {
                   if(errors.response.status=401)
                   {
                       window.location='/login';
                   }

               });


            }

        },

        computed:
        {
            buttonText()
        {
            return (this.status) ? 'Unfollow' : 'Follow';
        }
        }




    }
</script>
