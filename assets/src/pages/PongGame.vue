<template>
    <PageLayout title="Pong game">
        <h3>use K + M and D + C to move.</h3>
        <div class="pong-container">
            <div 
                class="pong-border"
                :style="calculatePongBorderPosition('left')" 
            />
            <div 
                class="pong-ball"
                :style="calculateBallPosition()"
            />
            <div 
                class="pong-border" 
                :style="calculatePongBorderPosition('right')"
            />
        </div>
    </PageLayout>
</template>

<script lang="ts">
import Vue from 'vue';
import PageLayout from '../components/PageLayout.vue';

export default Vue.extend({
    name: "PongGame",
    components: { PageLayout },
    data() {
        return {
            /**
             * The position of the left border
             */
            borderLeft: 45,
            /**
             * The position of the right border
             */
            borderRight: 45,
            /**
             * The cords of the ball
             */
            ball: {x: 50, y: 250},
            /**
             * The angle of the ball
             */
            ballAngle: 45,
            /**
             * The X direction 
             */
            directionX: -1,
            /**
             * The Y direction
             */
            directionY: -1
        }
    },
    methods: {
        /**
         * Changes the position of the pong
         * 
         * @param axis [x or y] The exis that should be changed
         * @param by The value the axis should be increased/decreased
         */
        changePongPosition(axis: string, by: number) {
            switch (axis) {
                case 'x':
                    this.ball.x += by;
                    break;
                case 'y':
                    this.ball.y += by;
                    break;    
            }
        },
        /**
         * Changes the position of the border
         * 
         * @param border [left or right] The border that should be changed
         * @param by The value the axis should be increased/decreased
         */
        changeBorderPosition(border: string, by: number) {
            switch (border) {
                case 'left':
                    if (this.checkMovePermitted(this.borderLeft, by)) {
                        this.borderLeft += by;
                    }
                    break;
                case 'right':
                    if (this.checkMovePermitted(this.borderRight, by)) {
                        this.borderRight += by;
                    }   
                    break;
            }
        },
        /**
         * Checks if the border can be changed
         * 
         * @param value the current border value
         * @param by The value the axis should be increased/decreased 
         */
        checkMovePermitted(value: number, by: number): boolean {
            if (by < 0) {
                return value !== 0;
            } else {
                return value < 90;
            }
        },
        /**
         * Calculates the position of the pong border
         * 
         * @param border [right or left] The border that should be changed
         */
        calculatePongBorderPosition(border: string) {
            switch (border) {
                case 'right': 
                    return 'top:' + this.borderRight * 5 + 'px';
                case 'left':
                    return 'top:' + this.borderLeft * 5 + 'px';    
            }
        },
        /**
         * Calculates the position of the pong
         */
        calculatePong() {
            if (this.ball.y > 470 || this.ball.y < 20) {
                this.directionY = this.directionY * -1;
            }
            this.changePongPosition('x', 3 * this.directionX);
            this.changePongPosition('y', 0.3 * this.ballAngle * this.directionY);
            if (this.ball.x > 100 || this.ball.x < -1) {
                if (
                    this.ball.x < 10 
                        && (this.ball.y < (this.borderLeft*5) + 60 && this.ball.y > (this.borderLeft*5) - 10)
                    || this.ball.x > 80 && this.ball.y < (this.borderRight*5) + 60 && this.ball.y > (this.borderRight*5) - 10
                ) {
                    this.directionX = this.directionX * -1;
                    this.directionY = this.directionY * -1;
                    this.ballAngle = this.ballAngle * -1;
                } else {
                    this.ball = {x: 50, y: 250};
                }
            }
        },
        /**
         * Calculates the position of the ball
         */
        calculateBallPosition() {
            return 'left:' + this.ball.x + '%;top:' + this.ball.y + 'px';
        }
    },
    mounted() {
        window.addEventListener('keydown', (e) => {
            switch (e.key) {
                case 'k':
                    this.changeBorderPosition('right', -5);
                    break;
                case 'm':
                    this.changeBorderPosition('right', 5);
                    break;
                case 'd':
                    this.changeBorderPosition('left', -5);
                    break;
                case 'c':
                    this.changeBorderPosition('left', 5);
                    break;        
            }
        });
        setInterval(this.calculatePong, 250);
    }
});
</script>

<style scoped>
    .pong-container {
        width: 90%;
        min-height: 500px;
        border: 1px solid black;
        display: grid;
        grid-template-columns: 5% 90% 5%;
    }
    .pong-border {
        height: 50px;
        position: relative;
        width: 10px;
        background: black;
        justify-self: center;
    }
    .pong-ball {
        width: 20px;
        height: 20px;
        background: red;
        position: relative;
    }
</style>