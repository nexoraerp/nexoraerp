<script setup>
import Window from './DashboardPreview/Window.vue';
import Header from './DashboardPreview/Header.vue';
import StatCard from './DashboardPreview/StatCard.vue';
import RevenueChart from './DashboardPreview/RevenueChart.vue';
import ActivityList from './DashboardPreview/ActivityList.vue';
</script>

<template>

<div
    class="relative"
>

    <!-- Glow -->

    <div
        class="absolute -inset-10 -z-10 rounded-full bg-blue-400/10 blur-3xl"
    />

    <Window>

        <Header />

        <div class="p-6 space-y-6">

            <!-- KPI -->

            <div
                class="grid grid-cols-2 gap-5"
            >

                <StatCard
                    title="Bugünkü Satış"
                    value="₺186.540"
                    change="+18%"
                />

                <StatCard
                    title="Kasa Bakiyesi"
                    value="₺985.200"
                    change="+11%"
                />

            </div>

            <!-- Grafik -->

            <RevenueChart />

            <!-- Hareketler -->

            <ActivityList />

        </div>

    </Window>

</div>

</template>